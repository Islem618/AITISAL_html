<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

$me = (int)$_SESSION['user_id'];

$sql = "
  SELECT 
    c.id AS conversation_id,
    MAX(m.created_at) AS last_message_at
  FROM conversation c
  JOIN conversation_user cu ON cu.conversation_id = c.id
  LEFT JOIN message m ON m.conversation_id = c.id
  WHERE cu.user_id = :me
  GROUP BY c.id
  ORDER BY last_message_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute(['me' => $me]);
$convs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($convs, JSON_UNESCAPED_UNICODE);
