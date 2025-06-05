<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $stmt = $conn->prepare("
      SELECT
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

    $out = [];
    foreach ($rows as $row) {
        $rawPhoto = (isset($row['photo_path']) && trim($row['photo_path']) !== '')
            ? $row['photo_path']
            : 'uploads/photos/pdp.png';
        $photoPath = $rawPhoto;  // ex “uploads/photos/profil_37.jpg” ou “uploads/photos/pdp.png”

        $rawMedia = (isset($row['media_path']) && trim($row['media_path']) !== '')
            ? $row['media_path']
            : '';
        $mediaPath = $rawMedia;  // ex “uploads/messages/msg_37_12345.jpg” ou “”

        $out[] = [
            'content'    => $row['content'],
            'created_at' => $row['created_at'],
            'prenom'     => $row['prenom'],
            'nom'        => $row['nom'],
            'photo_path' => $photoPath,
            'media_path' => $mediaPath
        ];
    }
    echo json_encode($out, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error'=>'Erreur interne'], JSON_UNESCAPED_UNICODE);
}