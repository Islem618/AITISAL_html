<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status'=>'error','message'=>'Non authentifié']);
    exit();
}

require_once __DIR__ . '/../db_connect.php'; // instancie $pdo

$input  = json_decode(file_get_contents('php://input'), true);
$toId   = (int) ($input['friend_id'] ?? 0);
$fromId = (int) $_SESSION['user_id'];

if ($toId <= 0 || $toId === $fromId) {
    echo json_encode(['status'=>'error','message'=>'Identifiant invalide']);
    exit();
}

$sql = "
  INSERT IGNORE INTO friendship (from_id, to_id)
  VALUES (?, ?)
";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$fromId, $toId])) {
    echo json_encode(['status'=>'success']);
} else {
    echo json_encode(['status'=>'error','message'=>'Erreur base']);
}
