<?php
// Démarrez la session au début de chaque page PHP
session_start();

// Assurez-vous que l'utilisateur est connecté avant de procéder
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: Connexion.php");
    exit();
}?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>TransNoise - EchoKey</title>
    <link rel="stylesheet" href="../css/normalize-user.css" />
    <link rel="stylesheet" href="../css/espaceuser.css" />
    <link rel="icon" type="image/x-icon" href="../../images/logonutritium.ico" />
  </head>

  <header>
      <nav>
          <ul class="menu">
              <li><a href="index.php">Home</a></li>
              <li><a href="chat.php">Let's Chat !</a></li>
              <li><a href="espaceuser.php">Profil</a></li>
          </ul>
      </nav>

  </header>
  <a href="index.php"><img src="../../images/logonutritium.png" id="Logo1" alt="Logo EchoKey" title="Logo EchoKey"></a> <!--logo EchoKey-->

  <div class="welcome-section">
      <h1>Bienvenue dans votre espace !</h1>
  </div>
  <!-- Pastille notification (en haut à droite) -->
  <span id="notification-badge">
  0
</span>

  <!-- Panneau des notifications (au-dessus du contenu, masqué par défaut) -->
  <section id="notification-box">
      <h3>Notifications</h3>
      <ul id="notification-panel"></ul>
  </section>


    <div class="main" style="width: 100%; height: 80%; float: left">
      <div class="img1" style="margin-top: 200px">
        <figure>
          <a href="profil.php">
            <img src="../../images/profil.png" id="img-profil" />
            <div class="image-text">profil</div>
          </a>
        </figure>
        <span></span>
      </div>

      <div class="img2" style="margin-top: 200px">
        <figure>
            <a href="interests.php">
            <img src="../../images/commandebutton.png" id="img-analyse" />
            <div class="image-text2">analyse</div>
          </a>
        </figure>
        <span></span>
      </div>
    </div>

  <img src="../../images/footernutritium.png" id="LogosFooter" alt="LogosFooter" title="LogosFooter"> <!--logo Transnoise-->

  <!--logo déconnexion-->
  <img src="../../images/déconnexion2.png" id="imgdeco" alt="logo déconnexion" title="logo déconnexion" onmouseover="changerImage('survol')" onmouseout="changerImage('normal')" onclick="deconnexion()">
<footer>
  <div class="footer">
  <nav>
    <ul>
      <li><a href="CGU.php" id="ga" target="_blank">CGU</a></li>
      <li><a href="https://www.isep.fr/" id="ga" target="_blank">Nos investisseurs</a></li>
      <li><a href="faq.php" id="ga" target="_blank">Contact</a></li>
    </ul>
  </nav>
</div>
</footer>
    <script src="../js/espaceuser.js"></script>
  <script src="../js/notifications.js"></script>
  </body>
</html>
