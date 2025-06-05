<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status'=>'error','message'=>'Non authentifié'], JSON_UNESCAPED_UNICODE);
    exit();
}

$content = trim($_POST['content'] ?? '');
$me      = (int)$_SESSION['user_id'];

// 1) Si conversation_id est défini → message privé
if (!empty($_POST['conversation_id'])) {
    $cid = (int)$_POST['conversation_id'];
    // Vérifier que l’utilisateur appartient bien à cette conversation
    $chk = $conn->prepare("
        SELECT 1 
          FROM conversation_user 
         WHERE conversation_id = :cid 
           AND user_id = :me 
         LIMIT 1
    ");
    $chk->execute(['cid'=>$cid,'me'=>$me]);
    if (!$chk->fetch()) {
        http_response_code(403);
        echo json_encode(['status'=>'error','message'=>'Accès refusé'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Gestion de l’upload d’une image (uniquement) si présente
    $mediaPath = '';
    if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
        $allowedExt = ['jpg','jpeg','png','gif'];
        $info = pathinfo($_FILES['media']['name']);
        $ext  = strtolower($info['extension'] ?? '');

        if (in_array($ext, $allowedExt)) {
            // → on veut ARRIVER dans FR\uploads\messages\
            $targetDir = __DIR__ . '/../../../uploads/messages/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fileName    = 'msg_' . $me . '_' . time() . '.' . $ext;
            $destination = $targetDir . $fileName;
            if (move_uploaded_file($_FILES['media']['tmp_name'], $destination)) {
                // Stocker en BDD le chemin relatif depuis FR/, par ex "uploads/messages/xxx.jpg"
                $mediaPath = 'uploads/messages/' . $fileName;
            }
        }
        // Si extension non autorisée, $mediaPath reste vide.
    }

    // Insertion dans la table message
    $stmt = $conn->prepare("
        INSERT INTO message (conversation_id, from_id, content, media_path)
        VALUES (:cid, :me, :content, :media_path)
    ");
    $ok = $stmt->execute([
        'cid'        => $cid,
        'me'         => $me,
        'content'    => $content,
        'media_path' => $mediaPath,
    ]);
    if ($ok) {
        echo json_encode(['status'=>'success'], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','message'=>'Erreur base messages'], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// 2) Sinon → post public (table `post`)
$mediaPath = '';
if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
    $allowedExt = ['jpg','jpeg','png','gif'];
    $info = pathinfo($_FILES['media']['name']);
    $ext  = strtolower($info['extension'] ?? '');

    if (in_array($ext, $allowedExt)) {
        // → toujours FR\uploads\messages\
        $targetDir = __DIR__ . '/../../../uploads/messages/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName    = 'msg_' . $me . '_' . time() . '.' . $ext;
        $destination = $targetDir . $fileName;
        if (move_uploaded_file($_FILES['media']['tmp_name'], $destination)) {
            $mediaPath = 'uploads/messages/' . $fileName;
        }
    }
}

$stmt = $conn->prepare("
    INSERT INTO post (author_id, content, media_path)
    VALUES (:author_id, :content, :media_path)
");
$ok = $stmt->execute([
    'author_id' => $me,
    'content'   => $content,
    'media_path'=> $mediaPath,
]);
if ($ok) {
    echo json_encode(['status'=>'success'], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Erreur base post'], JSON_UNESCAPED_UNICODE);
}