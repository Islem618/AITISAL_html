<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $conn->prepare("
      SELECT p.content, p.created_at, CONCAT(u.prenom, ' ', u.nom) AS username
      FROM post p
      JOIN user u ON p.author_id = u.id_User
      ORDER BY p.created_at ASC
    ");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($posts, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur interne']);
}
