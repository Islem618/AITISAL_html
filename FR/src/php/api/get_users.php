<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// Si l'utilisateur n'est pas connecté, on renvoie un tableau vide
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit();
}

// Inclure la connexion à la base (ajuste le chemin si besoin)
require_once __DIR__ . '/../db_connect.php';

$me = (int) $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT id_User, prenom, nom
    FROM `user`
    WHERE id_User <> ?
");
$stmt->bind_param("i", $me);
$stmt->execute();

$result = $stmt->get_result();
$users  = [];

while ($row = $result->fetch_assoc()) {
    $users[] = [
        'id'       => (int) $row['id_User'],
        'username' => trim($row['prenom'] . ' ' . $row['nom'])
    ];
}

echo json_encode($users);
