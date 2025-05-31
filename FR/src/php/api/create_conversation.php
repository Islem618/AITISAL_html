<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../db_connect.php';
if(!isset($_SESSION['logged_in'])) {
    http_response_code(401);
    exit;
}
$me    = (int)$_SESSION['user_id'];
$other = (int)($_POST['friend_id'] ?? 0);
if($other<=0 || $other===$me) {
    http_response_code(400);
    exit;
}

// 1) créer la conv
$conn->exec("INSERT INTO conversation () VALUES ()");
$cid = $conn->lastInsertId();

// 2) rattacher les deux
$stmt = $conn->prepare(
    "INSERT INTO conversation_user (conversation_id,user_id) VALUES (?,?)"
);
$stmt->execute([$cid,$me]);
$stmt->execute([$cid,$other]);

echo json_encode(['conversation_id'=> (int)$cid]);
