<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__.'/../db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit;
}

$me = (int)($_SESSION['user_id'] ?? $_SESSION['id_User'] ?? 0);
if ($me <= 0) {
    echo json_encode([]);
    exit;
}

try {
    $sql = "SELECT interest_id FROM user_interest WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$me]);
    $ids = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'interest_id');
    echo json_encode($ids, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([]);
}
