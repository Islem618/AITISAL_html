<?php
session_start();

// 1) Vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../php/login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$fieldName = 'profile_photo'; // correspond à name="profile_photo" dans le <input>

// 2) Vérifier qu'un fichier a été uploadé sans erreur
if ( ! isset($_FILES[$fieldName]) || ! is_array($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK ) {
    // Aucun fichier ou erreur lors de l'upload
    switch ($_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE) {
        case UPLOAD_ERR_NO_FILE:
            echo "Aucun fichier n’a été téléchargé.";
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo "Le fichier dépasse la taille autorisée (max 2 Mo).";
            break;
        case UPLOAD_ERR_PARTIAL:
            echo "Le fichier a été partiellement téléchargé.";
            break;
        default:
            echo "Erreur lors de l’upload du fichier.";
            break;
    }
    exit();
}

// 3) Extraire les informations du fichier
$tmpName      = $_FILES[$fieldName]['tmp_name'];
$originalName = $_FILES[$fieldName]['name'];
$fileSize     = (int) $_FILES[$fieldName]['size'];

// 4) Contrôler l’extension
$extension    = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
$allowedExts  = ['jpg','jpeg','png','gif'];
if (!in_array($extension, $allowedExts, true)) {
    echo "Extension non autorisée. Seuls JPG, PNG et GIF sont autorisés.";
    exit();
}

// 5) Contrôler la taille (ex : 2 Mo maxi)
$maxSize = 2 * 1024 * 1024; // 2 Mo
if ($fileSize > $maxSize) {
    echo "Le fichier est trop volumineux. Taille max : 2 Mo.";
    exit();
}

// 6) Construire le chemin absolu vers le dossier FINAL
// __DIR__ = FR/src/php/api
// Pour atteindre FR/uploads/photos on doit remonter trois fois : ../../../uploads/photos/
$uploadDir = __DIR__ . "/../../../uploads/photos/";
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
        echo "Impossible de créer le répertoire de destination.";
        exit();
    }
}

// 7) Générer un nom unique sur serveur, par ex. "profil_32.jpg"
$newFileName  = "profil_" . $user_id . "." . $extension;
$destination  = $uploadDir . $newFileName;

// 8) Déplacer le fichier temporaire vers le dossier final
if (!move_uploaded_file($tmpName, $destination)) {
    echo "Erreur lors du déplacement du fichier.";
    exit();
}

// 9) Mettre à jour la colonne photo_path en base (chemin relatif)
$relativePath = "uploads/photos/" . $newFileName;

$mysqli = new mysqli("localhost", "root", "", "siteweb");
if ($mysqli->connect_errno) {
    echo "Erreur de connexion à la BDD : " . $mysqli->connect_error;
    exit();
}

// S’assurer que la colonne photo_path existe bien dans la table user
// ALTER TABLE user ADD COLUMN IF NOT EXISTS photo_path VARCHAR(255) NULL;
$stmt = $mysqli->prepare("UPDATE user SET photo_path = ? WHERE id_User = ?");
if (!$stmt) {
    echo "Erreur de préparation de la requête : " . $mysqli->error;
    $mysqli->close();
    exit();
}
$stmt->bind_param("si", $relativePath, $user_id);
if (! $stmt->execute()) {
    echo "Erreur lors de la mise à jour en base : " . $stmt->error;
    $stmt->close();
    $mysqli->close();
    exit();
}
$stmt->close();
$mysqli->close();

// 10) Rediriger vers la page de profil pour afficher la nouvelle photo
header("Location: ../../php/profil.php");
exit();