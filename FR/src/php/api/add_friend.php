<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Non authentifié']);
    exit();
}

// Inclure la connexion à la base (ajuste le chemin si besoin)
require_once __DIR__ . '/../db_connect.php';

// Récupérer les données JSON envoyées
$input  = json_decode(file_get_contents('php://input'), true);
$toId   = isset($input['friend_id']) ? (int) $input['friend_id'] : 0;
$fromId = (int) $_SESSION['user_id'];

// Validation
if ($toId === 0 || $toId === $fromId) {
    echo json_encode(['status' => 'error', 'message' => 'Identifiant invalide']);
    exit();
}

// On insère la relation d’amitié (IGNORE pour éviter doublons)
$stmt = $conn->prepare("
    INSERT IGNORE INTO friendship (from_id, to_id)
    VALUES (?, ?)
");
$stmt->bind_param("ii", $fromId, $toId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    // En cas d’erreur SQL
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erreur base']);
}
