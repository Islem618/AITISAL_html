<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status'=>'error','message'=>'Non authentifié'], JSON_UNESCAPED_UNICODE);
    exit();
}

$input   = json_decode(file_get_contents('php://input'), true);
$content = trim($input['content'] ?? '');
if ($content === '') {
    echo json_encode(['status'=>'error','message'=>'Message vide'], JSON_UNESCAPED_UNICODE);
    exit();
}

$me = (int)$_SESSION['user_id'];

// 1) Si on a une conversation_id, envoi privé
if (!empty($input['conversation_id'])) {
    $cid = (int)$input['conversation_id'];
    // 1.a) Vérifier que l’utilisateur appartient à la conv
    $chk = $conn->prepare("
      SELECT 1 FROM conversation_user
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
    // 1.b) Insérer dans message
    $stmt = $conn->prepare("
      INSERT INTO message (conversation_id, from_id, content)
      VALUES (:cid, :me, :content)
    ");
    if ($stmt->execute(['cid'=>$cid,'me'=>$me,'content'=>$content])) {
        echo json_encode(['status'=>'success'], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','message'=>'Erreur base chat'], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// 2) Sinon, on poste sur le mur public (table `post`)
$stmt = $conn->prepare("
  INSERT INTO post (author_id, content)
  VALUES (:author_id, :content)
");
if ($stmt->execute(['author_id'=>$me,'content'=>$content])) {
    echo json_encode(['status'=>'success'], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Erreur base post'], JSON_UNESCAPED_UNICODE);
}
