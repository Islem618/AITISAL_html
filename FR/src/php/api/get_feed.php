<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php'; // Adaptez le chemin si nécessaire

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Sélection de tous les posts publics, avec l’avatar de l’auteur (photo_path) et le media_path
    $stmt = $conn->prepare("
      SELECT
        p.id,
        p.content,
        p.created_at,
        p.media_path,
        u.prenom,
        u.nom,
        u.photo_path
      FROM post p
      JOIN user u ON p.author_id = u.id_User
      ORDER BY p.created_at ASC
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = [];
    foreach ($rows as $row) {
        // Si l’utilisateur n’a pas de photo de profil, on utilise l’image par défaut
        $photoPath = (isset($row['photo_path']) && trim($row['photo_path']) !== '')
            ? $row['photo_path']
            : 'uploads/photos/pdp.png';

        // Si le post a un média, on l’inclut, sinon on laisse null
        $mediaPath = null;
        if (!empty($row['media_path'])) {
            $mediaPath = $row['media_path'];
        }

        $output[] = [
            'id'         => (int)$row['id'],
            'content'    => $row['content'],
            'created_at' => $row['created_at'],
            'prenom'     => $row['prenom'],
            'nom'        => $row['nom'],
            'photo_path' => $photoPath,
            'media_path' => $mediaPath
        ];
    }

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error'=>'Erreur interne'], JSON_UNESCAPED_UNICODE);
}