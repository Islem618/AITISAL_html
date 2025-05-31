<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

$me  = (int)$_SESSION['user_id'];
$cid = isset($_GET['conversation_id']) ? (int)$_GET['conversation_id'] : 0;
if ($cid <= 0) {
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

// 1) Vérifier que l’utilisateur appartient bien à cette conversation
$chk = $conn->prepare("
  SELECT 1 FROM conversation_user
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

// 2) Récupérer les messages
$sql = "
  SELECT
    m.id,
    m.from_id,
    m.content,
    m.created_at,
    u.prenom,
    u.nom
  FROM message m
  JOIN user u ON m.from_id = u.id_User
  WHERE m.conversation_id = :cid
  ORDER BY m.created_at ASC
";
$stmt = $conn->prepare($sql);
$stmt->execute(['cid' => $cid]);
$msgs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($msgs, JSON_UNESCAPED_UNICODE);
