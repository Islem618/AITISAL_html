<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// Récupération de la connexion PDO (ou adaptez selon votre configuration)
require_once __DIR__ . '/../db_connect.php';

// Vérification de la session
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit;
}

try {
    // On récupère : contenu, date, prénom, nom et photo_path (s’il existe)
    $stmt = $conn->prepare("
      SELECT
        p.id,
        p.content,
        p.created_at,
        u.prenom,
        u.nom,
        u.photo_path
      FROM post AS p
      JOIN user AS u ON p.author_id = u.id_User
      ORDER BY p.created_at ASC
    ");
    $stmt->execute();

    $posts = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Si l’utilisateur n’a pas de photo (NULL ou vide), on prend l’image par défaut pdp.png
        $photoPath = (isset($row['photo_path']) && trim($row['photo_path']) !== '')
            ? $row['photo_path']
            : 'uploads/photos/pdp.png';

        $posts[] = [
            'id'         => (int)$row['id'],
            'content'    => $row['content'],
            'created_at' => $row['created_at'],
            'prenom'     => $row['prenom'],
            'nom'        => $row['nom'],
            'photo_path' => $photoPath
        ];
    }

    echo json_encode($posts, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur interne']);
}