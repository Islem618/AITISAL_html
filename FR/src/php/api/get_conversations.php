<?php
session_start();
header('Content-Type: application/json');

// 1) Vérification de l’authentification
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Non authentifié']);
    exit;
}

// 2) Récupération de l’ID utilisateur
$me = (int) $_SESSION['user_id'];

// 3) Connexion à la BDD via ton db_connect.php
require_once __DIR__ . '/../db_connect.php';  // définit $pdo et alias $conn

// 4) Requête : récupérer les conversations + date du dernier message
$sql = "
  SELECT
    c.id AS conversation_id,
    MAX(m.created_at) AS last_message_at
  FROM conversation c
  JOIN conversation_user cu
    ON cu.conversation_id = c.id
  LEFT JOIN message m
    ON m.conversation_id = c.id
  WHERE cu.user_id = :me
  GROUP BY c.id
  ORDER BY last_message_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['me' => $me]);
$convs = $stmt->fetchAll();

// 5) Retour JSON
echo json_encode($convs, JSON_UNESCAPED_UNICODE);
