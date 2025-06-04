<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Non authentifié']);
    exit;
}

require_once __DIR__ . '/../db_connect.php';

$me = (int)($_SESSION['user_id'] ?? $_SESSION['id_User'] ?? 0);
if ($me <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur invalide']);
    exit;
}

try {
    $sqlUpdate = "UPDATE `user` SET last_notif_checked = NOW() WHERE id_User = ?";
    $stmtUpd = $conn->prepare($sqlUpdate);
    $stmtUpd->execute([$me]);

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erreur interne']);
}