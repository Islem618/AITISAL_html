<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Non authentifié']);
    exit();
}

require_once __DIR__ . '/../db_connect.php';

// Récupération du JSON envoyé par fetch()
$input = json_decode(file_get_contents('php://input'), true);
$content = trim($input['content'] ?? '');

if ($content === '') {
    echo json_encode(['status' => 'error', 'message' => 'Message vide']);
    exit();
}

$authorId = (int) $_SESSION['user_id'];

// Préparation PDO avec des placeholders nommés
$sql = "INSERT INTO post (author_id, content) VALUES (:author_id, :content)";
$stmt = $conn->prepare($sql);

// Exécution en passant un tableau associatif
if ($stmt->execute([
    'author_id' => $authorId,
    'content'   => $content
])) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error',   'message' => 'Erreur base']);
}
