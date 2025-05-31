<?php
// ─────────────────────────────────────────────────────────────────────────────
//   api/add_friend.php
//   Version simplifiée : on crée la conversation et on lie chaque utilisateur
//   dans deux requêtes DISTINCTES pour éviter tout "Invalid parameter".
// ─────────────────────────────────────────────────────────────────────────────

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');
session_start();

// 1) On vérifie que l’utilisateur est connecté
if (! isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Non authentifié'
    ]);
    exit;
}

// 2) On inclut la connexion à la BDD
require_once __DIR__ . '/../db_connect.php';
// Ici, db_connect.php crée un PDO dans $pdo et un alias $conn = $pdo.

// 3) On récupère l’ID de l’utilisateur (depuis la session)
$me = 0;
if (isset($_SESSION['user_id'])) {
    $me = (int) $_SESSION['user_id'];
} elseif (isset($_SESSION['id_User'])) {
    $me = (int) $_SESSION['id_User'];
}
if ($me <= 0) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'ID utilisateur invalide'
    ]);
    exit;
}

// 4) On lit le JSON POST pour récupérer "friend_id"
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

if (! isset($input['friend_id'])) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Paramètre "friend_id" manquant'
    ]);
    exit;
}

$friendId = (int) $input['friend_id'];
if ($friendId <= 0) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'ID de l’ami invalide'
    ]);
    exit;
}
if ($friendId === $me) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Vous ne pouvez pas ajouter vous‐même'
    ]);
    exit;
}

try {
    // ─── 5) On vérifie qu’ils ne sont pas déjà amis (bidirectionnel)
    $sql_check = "
      SELECT 1
        FROM friendship
       WHERE (from_id = ? AND to_id = ?)
          OR (from_id = ? AND to_id = ?)
       LIMIT 1
    ";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([
        $me,       $friendId,
        $friendId, $me
    ]);
    if ($stmt_check->fetch()) {
        // déjà dans la table friendship
        echo json_encode([
            'status'  => 'error',
            'message' => 'Vous êtes déjà amis'
        ]);
        exit;
    }

    // ─── 6) On insère dans friendship (tâche 1)
    $sql_insert_friend = "
      INSERT INTO friendship (from_id, to_id)
      VALUES (?, ?)
    ";
    $stmt_friend = $pdo->prepare($sql_insert_friend);
    $ok = $stmt_friend->execute([
        $me,      // premier ?
        $friendId // second ?
    ]);
    if (! $ok) {
        throw new Exception('Impossible d’ajouter dans friendship');
    }

    // ─── 7) On crée une NOUVELLE conversation
    $sql_insert_conv = "
      INSERT INTO conversation (created_at)
      VALUES (NOW())
    ";
    $stmt_conv = $pdo->prepare($sql_insert_conv);
    $stmt_conv->execute();
    $conversationId = (int) $pdo->lastInsertId();
    if ($conversationId <= 0) {
        throw new Exception('Impossible de créer la conversation');
    }

    // ─── 8) On lie l’utilisateur “me” à la conversation
    $sql_cu_me = "
      INSERT INTO conversation_user (conversation_id, user_id)
      VALUES (?, ?)
    ";
    $stmt_cu_me = $pdo->prepare($sql_cu_me);
    $ok2 = $stmt_cu_me->execute([
        $conversationId,
        $me
    ]);
    if (! $ok2) {
        throw new Exception('Impossible de lier “me” à la conversation');
    }

    // ─── 9) On lie l’autre utilisateur (“friendId”) à la conversation
    $stmt_cu_friend = $pdo->prepare($sql_cu_me);
    $ok3 = $stmt_cu_friend->execute([
        $conversationId,
        $friendId
    ]);
    if (! $ok3) {
        throw new Exception('Impossible de lier “friend” à la conversation');
    }

    // ─── 10) Tout s’est bien passé → on renvoie success + conversation_id
    echo json_encode([
        'status'          => 'success',
        'conversation_id' => $conversationId
    ]);

} catch (Exception $e) {
    // En cas d’erreur SQL ou autre, on renvoie un code 500 et le message détaillé
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Erreur interne : ' . $e->getMessage()
    ]);
}

?>
