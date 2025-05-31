<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

// 1) Si pas connecté, on renvoie un tableau vide
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo '[]';
    exit;
}

// 2) Récupérer l’ID de l’utilisateur
$me = 0;
if (isset($_SESSION['user_id'])) {
    $me = (int) $_SESSION['user_id'];
} elseif (isset($_SESSION['id_User'])) {
    $me = (int) $_SESSION['id_User'];
}
if ($me <= 0) {
    echo '[]';
    exit;
}

// 3) Requête SQL minimale avec placeholders positionnels
$sql = "
  SELECT
    c.id AS conversation_id,
    MAX(m.created_at) AS last_message_at,
    u_other.prenom    AS friend_prenom,
    u_other.nom       AS friend_nom
  FROM conversation c

    JOIN conversation_user cu_me
      ON cu_me.conversation_id = c.id
     AND cu_me.user_id = ?

    JOIN conversation_user cu_other
      ON cu_other.conversation_id = c.id
     AND cu_other.user_id <> ?

    JOIN user u_other
      ON u_other.id_User = cu_other.user_id

    LEFT JOIN message m
      ON m.conversation_id = c.id

  WHERE cu_me.user_id = ?
  GROUP BY c.id, u_other.prenom, u_other.nom
  ORDER BY last_message_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute([$me, $me, $me]);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
