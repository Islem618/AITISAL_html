<?php
// ─── FR/src/php/api/create_conversation.php ───────────────────────

session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

// 1) Vérifier que l’utilisateur est connecté
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Non authentifié']);
    exit;
}

// 2) Extraire “me” depuis la session
$me = (int) ( $_SESSION['user_id'] ?? $_SESSION['id_User'] ?? 0 );
if ($me <= 0) {
    echo json_encode(['status'=>'error','message'=>'ID utilisateur invalide']);
    exit;
}

// 3) Lire le JSON brut pour récupérer “friend_id”
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

if (!isset($input['friend_id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Paramètre "friend_id" manquant']);
    exit;
}

$other = (int)$input['friend_id'];
if ($other <= 0 || $other === $me) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Paramètre friend_id invalide']);
    exit;
}

try {
    // 4) (Optionnel) : vérifier qu’il n’existe pas déjà une conversation entre me/other
    $sql_check = "
      SELECT c.id
        FROM conversation c
        JOIN conversation_user cu1 ON cu1.conversation_id = c.id AND cu1.user_id = :me
        JOIN conversation_user cu2 ON cu2.conversation_id = c.id AND cu2.user_id = :other
        LIMIT 1
    ";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute(['me'=>$me, 'other'=>$other]);
    if ($row = $stmt_check->fetch(PDO::FETCH_ASSOC)) {
        // Si une conversation existe déjà, on renvoie son ID
        echo json_encode([
            'status'          => 'success',
            'conversation_id' => (int)$row['id']
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 5) Créer une nouvelle conversation (INSERT INTO conversation)
    $sql_conv = "INSERT INTO conversation (created_at) VALUES (NOW())";
    $stmt     = $conn->prepare($sql_conv);
    $stmt->execute();
    $cid = (int)$conn->lastInsertId();
    if ($cid <= 0) {
        throw new Exception('Impossible de créer la conversation');
    }

    // 6) Lier “me” et “other” à cette conversation (table conversation_user)
    $sql_cu = "INSERT INTO conversation_user (conversation_id, user_id) VALUES (?, ?)";
    $stmt_cu = $conn->prepare($sql_cu);
    $stmt_cu->execute([$cid, $me]);
    $stmt_cu->execute([$cid, $other]);

    // 7) Répondre “success” + conversation_id
    echo json_encode([
        'status'          => 'success',
        'conversation_id' => $cid
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Erreur interne : ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
