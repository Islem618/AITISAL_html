<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

// 1) Vérifier session
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit;
}
$me = (int) ($_SESSION['user_id'] ?? $_SESSION['id_User'] ?? 0);
if ($me <= 0) {
    echo json_encode([]);
    exit;
}

// 2) Récupérer tous les intérêts, puis marquer ceux déjà affectés à l’utilisateur
$sql = "
  SELECT 
    i.id,
    i.label,
    (ui.user_id IS NOT NULL) AS selected
  FROM interest i
  LEFT JOIN user_interest ui 
    ON ui.interest_id = i.id 
   AND ui.user_id = ?
  ORDER BY i.label ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([ $me ]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3) Retourner JSON
echo json_encode($rows, JSON_UNESCAPED_UNICODE);