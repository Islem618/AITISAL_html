<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Non authentifié']);
    exit();
}

require_once __DIR__ . '/../db_connect.php'; // définit $conn

// Récupération du JSON envoyé par fetch()
$input   = json_decode(file_get_contents('php://input'), true);
$content = trim($input['content'] ?? '');

if ($content === '') {
    echo json_encode(['status' => 'error', 'message' => 'Message vide']);
    exit();
}

$authorId = (int) $_SESSION['user_id'];

// ** 1) Si on a un conversation_id, on insère dans la table `message` ** //
if (isset($input['conversation_id'])) {
    $cid = (int) $input['conversation_id'];

    // 1.a) Vérifier que l’utilisateur fait partie de la conversation
    $sql  = "SELECT 1 FROM conversation_user 
               WHERE conversation_id = :cid 
                 AND user_id = :me 
               LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['cid' => $cid, 'me' => $authorId]);
    if (!$stmt->fetch()) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Accès refusé']);
        exit();
    }

    // 1.b) Insertion dans `message`
    $sql = "INSERT INTO message (conversation_id, from_id, content)
            VALUES (:cid, :me, :content)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([
        'cid'     => $cid,
        'me'      => $authorId,
        'content' => $content
    ])) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error',   'message' => 'Erreur base chat']);
    }
    exit();
}

// ** 2) Sinon, on retombe sur l’ancien code (mur public) ** //
$sql  = "INSERT INTO post (author_id, content) VALUES (:author_id, :content)";
$stmt = $conn->prepare($sql);
if ($stmt->execute([
    'author_id' => $authorId,
    'content'   => $content
])) {
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erreur base post']);
}
