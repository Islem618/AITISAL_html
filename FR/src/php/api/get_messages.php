<?php
session_start();
header('Content-Type: application/json');

// 1) Connexion à la BDD
require_once __DIR__ . '/../db_connect.php';  // ou config.php si c'est là ta connexion

// 2) Vérif. authentification
if (!isset($_SESSION['id_User'])) {
    http_response_code(401);
    echo json_encode(['error'=>'Non authentifié']);
    exit;
}
$me = (int) $_SESSION['id_User'];

// 3) Récupérer l’ID de la conversation
if (empty($_GET['conversation_id'])) {
    http_response_code(400);
    echo json_encode(['error'=>'conversation_id manquant']);
    exit;
}
$cid = (int) $_GET['conversation_id'];

// 4) Vérifier que l’utilisateur participe bien à cette conversation
$sql = "SELECT 1
          FROM conversation_user
         WHERE conversation_id = :cid
           AND user_id = :me
         LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['cid'=>$cid, 'me'=>$me]);
if (!$stmt->fetch()) {
    http_response_code(403);
    echo json_encode(['error'=>'Accès refusé']);
    exit;
}

// 5) Charger les messages
$sql = "
  SELECT m.id,
         m.from_id,
         u.prenom,
         u.nom,
         m.content,
         DATE_FORMAT(m.created_at, '%Y-%m-%d %H:%i:%s') AS created_at
    FROM message m
    JOIN user u ON u.id_User = m.from_id
   WHERE m.conversation_id = :cid
   ORDER BY m.created_at ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['cid'=>$cid]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 6) Envoyer le JSON
echo json_encode($messages, JSON_UNESCAPED_UNICODE);
