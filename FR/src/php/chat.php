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
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../css/normalize.css" />
    <link rel="stylesheet" href="../css/chat.css" />
    <script src="../js/jquery.min.js"></script>
    <link rel="icon" href="../../images/logonutritium.ico" />
    <title>Let's Chat !</title>
</head>
<body>
<!-- Barre de menu -->
<nav>
    <ul class="menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="chat.php">Let's Chat !</a></li>
        <li><a href="espaceuser.php">Profile</a></li>
    </ul>
</nav>

<!-- Titre principal -->
<h1>Commençons à discuter et trouvez vos amis !</h1>

<!-- Pastille notification (en haut à droite) -->
<span id="notification-badge">
  0
</span>

<!-- Panneau des notifications (au-dessus du contenu, masqué par défaut) -->
<section id="notification-box">
    <h3>Notifications</h3>
    <ul id="notification-panel"></ul>
</section>

<!-- Conteneur central -->
<div class="container clearfix">
    <!-- Section “Mes conversations” (gauche) -->
    <section id="conversations-section" class="section-box">
        <h2>Mes conversations</h2>
        <ul id="conversation-list">
            <!-- Peuplement par chat.js -->
        </ul>
    </section>

    <!-- Section “Chat” (centre) : LE BOUTON “← Chat public” SERA INSÉRÉ PAR LE JS -->
    <section id="chat-section" class="section-box">
        <div class="chat-container">
            <div id="chat-feed">
                <!-- Messages / posts insérés par chat.js -->
            </div>
            <form id="chat-form" class="chat-form">
                <input type="text" id="chat-input" placeholder="Écrivez un message …" autocomplete="off" />
                <button type="submit">Envoyer</button>
            </form>
        </div>
    </section>

    <!-- Section “Amis potentiels” (droite) -->
    <section id="friends-section" class="section-box">
        <h2>Amis potentiels</h2>
        <ul id="friend-candidates-list">
            <!-- Peuplement par chat.js -->
        </ul>
    </section>
</div>

<!-- Pied de page & logos -->
<img src="../../images/footernutritium.png" id="LogosFooter" alt="Footer Logos" />
<img src="../../images/déconnexion2.png" id="imgdeco"
     alt="Déconnexion" title="Déconnexion"
     onmouseover="changerImage('survol')"
     onmouseout="changerImage('normal')"
     onclick="deconnexion()" />

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

<!-- Injection de l’ID utilisateur au JS -->
<script>
    window.currentUserId = <?= (int)($_SESSION['user_id'] ?? $_SESSION['id_User']) ?>;
</script>
<script src="../js/chat.js"></script>
<script src="../js/notifications.js"></script>
</body>
</html>
