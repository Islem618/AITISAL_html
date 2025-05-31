<?php
// ─── FR/src/php/api/get_conversation.php ─────────────────────────

session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

// Vérifier la session
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['conversation_id' => 0]);
    exit;
}

$me       = (int)($_SESSION['user_id'] ?? 0);
$friendId = (int)($_GET['friend_id'] ?? 0);

if ($me <= 0 || $friendId <= 0 || $friendId === $me) {
    // Aucun friend_id valide → renvoyer 0
    echo json_encode(['conversation_id' => 0]);
    exit;
}

// Requête qui cherche une conversation où me et friendId sont tous les deux présents
$sql = "
  SELECT c.id AS conversation_id
    FROM conversation c
    JOIN conversation_user cu1 ON cu1.conversation_id = c.id AND cu1.user_id = :me
    JOIN conversation_user cu2 ON cu2.conversation_id = c.id AND cu2.user_id = :friend
    LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->execute(['me' => $me, 'friend' => $friendId]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    echo json_encode(['conversation_id' => (int)$row['conversation_id']]);
} else {
    echo json_encode(['conversation_id' => 0]);
}
