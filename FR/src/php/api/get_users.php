<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// Si l’utilisateur n’est pas connecté, on renvoie un tableau vide
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit();
}

require_once __DIR__ . '/../db_connect.php'; // instancie $pdo

$me = 0;
if (isset($_SESSION['user_id'])) {
    $me = (int) $_SESSION['user_id'];
} elseif (isset($_SESSION['id_User'])) {
    $me = (int) $_SESSION['id_User'];
}

if ($me <= 0) {
    echo json_encode([]);
    exit();
}

// Sélectionne tous les utilisateurs sauf moi-même
$sql = "SELECT id_User, prenom, nom 
          FROM user 
         WHERE id_User <> ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$me]);

$users = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $users[] = [
        'id'       => (int) $row['id_User'],
        'username' => trim($row['prenom'] . ' ' . $row['nom'])
    ];
}

// On renvoie le tableau JSON des utilisateurs
echo json_encode($users);
