<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

// On vérifie la session, facultatif si déjà fait dans chat.php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $conn->prepare("
      SELECT p.content, p.created_at, u.prenom AS username
      FROM post p
      JOIN user u ON p.author_id = u.id_User
      ORDER BY p.created_at ASC
    ");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($posts);
} catch (Exception $e) {
    // en prod tu pourrais logger l'erreur au lieu de l'afficher
    http_response_code(500);
    echo json_encode(['error' => 'Erreur interne']);
}
