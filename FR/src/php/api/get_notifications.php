<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit;
}

$me = (int) ($_SESSION['user_id'] ?? $_SESSION['id_User'] ?? 0);
if ($me <= 0) {
    echo json_encode([]);
    exit;
}

try {
    // Dernière consultation
    $stmtFetch = $conn->prepare("SELECT last_notif_checked FROM user WHERE id_User = ?");
    $stmtFetch->execute([$me]);
    $row = $stmtFetch->fetch(PDO::FETCH_ASSOC);
    $lastChecked = $row['last_notif_checked'] ?? '1970-01-01 00:00:00';

    $notifications = [];

    // Messages privés récents avec info utilisateur
    $stmtMsgs = $conn->prepare("
        SELECT 
            m.id,
            m.from_id,
            m.conversation_id,
            m.content,
            m.created_at,
            u.prenom,
            u.nom
        FROM message AS m
        JOIN conversation_user AS cu ON cu.conversation_id = m.conversation_id
        JOIN user AS u ON u.id_User = m.from_id
        WHERE cu.user_id = ?
          AND m.from_id <> ?
          AND m.created_at > ?
    ");
    $stmtMsgs->execute([$me, $me, $lastChecked]);

    foreach ($stmtMsgs as $msg) {
        $fullName = trim($msg['prenom'] . ' ' . $msg['nom']);
        $notifications[] = [
            'type' => 'new_private_message',
            'source_user_id' => (int)$msg['from_id'],
            'conversation_id' => (int)$msg['conversation_id'],
            'excerpt' => mb_substr($msg['content'], 0, 50),
            'event_time' => $msg['created_at'],
            'text' => "Vous avez reçu un message de {$fullName}"
        ];
    }

    // Tri chronologique
    usort($notifications, fn($a, $b) => strtotime($a['event_time']) <=> strtotime($b['event_time']));

    // Mise à jour de la colonne last_notif_checked
    $stmtUpdate = $conn->prepare("UPDATE user SET last_notif_checked = NOW() WHERE id_User = ?");
    $stmtUpdate->execute([$me]);

    echo json_encode($notifications, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}