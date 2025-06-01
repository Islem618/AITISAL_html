<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__.'/../db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status'=>'error','message'=>'Non authentifié']);
    exit;
}

$me = (int)($_SESSION['user_id'] ?? $_SESSION['id_User'] ?? 0);
if ($me <= 0) {
    echo json_encode(['status'=>'error','message'=>'Utilisateur invalide']);
    exit;
}

// Lire le JSON POST
$input = json_decode(file_get_contents('php://input'), true);
$selected = $input['interests'] ?? [];
if (!is_array($selected)) {
    echo json_encode(['status'=>'error','message'=>'Format inattendu']);
    exit;
}

try {
    // 1) Supprimer d'abord tous les anciens liens
    $del = $conn->prepare("DELETE FROM user_interest WHERE user_id = ?");
    $del->execute([$me]);

    // 2) Ré-inserer les choix courants (si au moins 1)
    if (!empty($selected)) {
        $ins = $conn->prepare("INSERT INTO user_interest (user_id, interest_id) VALUES (?, ?)");
        foreach ($selected as $iid) {
            $iid = (int)$iid;
            if ($iid > 0) {
                $ins->execute([$me, $iid]);
            }
        }
    }

    echo json_encode(['status'=>'success'], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Erreur interne']);
}