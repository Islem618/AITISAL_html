<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit();
}

require_once __DIR__ . '/../db_connect.php'; // instancie $pdo

$me = (int) $_SESSION['user_id'];

$sql = "
  SELECT id_User, prenom, nom
    FROM user
   WHERE id_User <> ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$me]);

$users = [];
while ($row = $stmt->fetch()) {
    $users[] = [
        'id'       => (int)$row['id_User'],
        'username' => trim($row['prenom'] . ' ' . $row['nom'])
    ];
}

echo json_encode($users);
