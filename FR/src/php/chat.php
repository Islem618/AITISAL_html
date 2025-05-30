<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: connexion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../css/normalize.css" />
    <link rel="stylesheet" href="../css/chat.css" />
    <script src="../js/jquery.min.js"></script>
    <link rel="icon" href="../../images/logonutritium.ico" />
    <title>Let's Chat !</title>
</head>
<body>
<header>
    <nav>
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="chat.php">Let's Chat !</a></li>
            <li><a href="espaceuser.php">Profile</a></li>
        </ul>
    </nav>
</header>

<a href="index.php">
    <img src="../../images/logonutritium.png" id="Logo1" alt="Logo Nutritium">
</a>

<h1>Commençons à discuter et trouvez vos amis !</h1>

<!-- ─── SECTION : Conversations privées ─────────────────────────── -->
<section id="conversations-section">
    <h2>Mes conversations</h2>
    <ul id="conversation-list"><!-- peuplé dynamiquement par chat.js --></ul>
</section>

<!-- ─── SECTION : Ajouter un ami ───────────────────────────────── -->
<section id="friends-section">
    <h2>Ajouter un ami</h2>
    <ul id="user-list"><!-- peuplé dynamiquement par chat.js --></ul>
</section>

<!-- ─── CONTENU DU CHAT / MUR PUBLIC ─────────────────────────────── -->
<div id="chat-feed" class="chat-feed"></div>

<form id="chat-form" class="chat-form">
    <input type="text" id="chat-input" placeholder="Écrivez un message…" autocomplete="off" />
    <button type="submit">Envoyer</button>
</form>

<img src="../../images/footernutritium.png" id="LogosFooter" alt="Footer Logos">
<img src="../../images/déconnexion2.png" id="imgdeco"
     alt="Déconnexion" title="Déconnexion"
     onmouseover="changerImage('survol')"
     onmouseout="changerImage('normal')"
     onclick="deconnexion()">

<footer>
    <div class="footer">
        <nav>
            <ul>
                <li><a href="CGU.php" target="_blank">C.G.U</a></li>
                <li><a href="https://www.isep.fr/" target="_blank">Nos investisseurs</a></li>
                <li><a href="faq.php" target="_blank">Contact</a></li>
            </ul>
        </nav>
    </div>
</footer>

<!-- expose l’ID de l’utilisateur au JS -->
<script>
    window.currentUserId = <?= (int)($_SESSION['user_id'] ?? $_SESSION['id_User']) ?>;
</script>
<script src="../js/chat.js"></script>
</body>
</html>
