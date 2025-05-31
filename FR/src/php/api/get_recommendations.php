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

$sql = "
  SELECT
    u.id_User           AS suggestion_id,
    u.prenom            AS suggestion_prenom,
    u.nom               AS suggestion_nom,
    COALESCE(mutual.count_mutual, 0) AS mutual_count
  FROM user u
  LEFT JOIN (
      SELECT f2.to_id AS fof_id,
             COUNT(*) AS count_mutual
        FROM friendship f1
        JOIN friendship f2
          ON f2.from_id = f1.to_id
       WHERE f1.from_id = ?
         AND f2.to_id <> ?
       GROUP BY f2.to_id
  ) AS mutual
    ON mutual.fof_id = u.id_User
  LEFT JOIN friendship ex
    ON ex.from_id = ?
   AND ex.to_id = u.id_User
  WHERE u.id_User <> ?
    AND ex.from_id IS NULL
  ORDER BY mutual_count DESC, u.prenom ASC
  LIMIT 10
";

$stmt = $conn->prepare($sql);
$stmt->execute([ $me, $me, $me, $me ]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_UNESCAPED_UNICODE);
