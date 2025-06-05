<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';  // On est déjà dans FR/src/php/api

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

$me  = (int)($_SESSION['user_id'] ?? 0);
$cid = isset($_GET['conversation_id']) ? (int)$_GET['conversation_id'] : 0;
if ($cid <= 0) {
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

// Vérifier que l’utilisateur appartient à cette conversation
$chk = $conn->prepare("
    SELECT 1
      FROM conversation_user
     WHERE conversation_id = :cid
       AND user_id = :me
     LIMIT 1
");
$chk->execute(['cid'=>$cid,'me'=>$me]);
if (!$chk->fetch()) {
    http_response_code(403);
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

// Récupérer les messages + media_path + photo_path de l’auteur
$sql = "
    SELECT
      m.id,
      m.from_id,
      m.content,
      m.created_at,
      m.media_path,
      u.prenom,
      u.nom,
      u.photo_path
    FROM message m
    JOIN user    u ON m.from_id = u.id_User
   WHERE m.conversation_id = :cid
   ORDER BY m.created_at ASC
";
$stmt = $conn->prepare($sql);
$stmt->execute(['cid'=>$cid]);

$msgs = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Photo de profil : si vide, on renvoie le chemin par défaut
    $rawPhoto = (isset($row['photo_path']) && trim($row['photo_path']) !== '')
        ? $row['photo_path']
        : 'uploads/photos/pdp.png';

    // Media image : si présent, on le renvoie, sinon chaîne vide
    $rawMedia = (isset($row['media_path']) && trim($row['media_path']) !== '')
        ? $row['media_path']
        : '';

    // On renvoie directement le chemin relatif tel quel (sans "src/")
    // JS ajoutera "../.." devant pour atteindre FR/uploads/...
    $photoPath = $rawPhoto;
    $mediaPath = $rawMedia !== '' ? 'src/' . ltrim($rawMedia, '/') : '';

    $msgs[] = [
        'id'         => (int)$row['id'],
        'from_id'    => (int)$row['from_id'],
        'content'    => $row['content'],
        'created_at' => $row['created_at'],
        'prenom'     => $row['prenom'],
        'nom'        => $row['nom'],
        'photo_path' => $photoPath,
        'media_path' => $mediaPath
    ];
}

echo json_encode($msgs, JSON_UNESCAPED_UNICODE);