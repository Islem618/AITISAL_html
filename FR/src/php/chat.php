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
    <link rel="stylesheet" href="../css/chat.css" />
    <link rel="stylesheet" href="../css/normalize.css" />
    <script src="../js/jquery.min.js"></script>

    <link rel="icon" type="image/x-icon" href="../../images/logonutritium.ico" />
    <title>Akatsuki - Nutritium</title>
</head>

<body>
    <header>
        <nav>
            <ul class="menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="chat.php">Let's chat !</a></li>
                <li><a href="espaceuser.php">Profile</a></li>
            </ul>
        </nav>
    </header>
    <a href="index.php"><img src="../../images/logonutritium.png" id="Logo1" alt="Logo EchoKey" title="Logo EchoKey"></a> <!--logo EchoKey-->

    <h1>Commençons à discuter et trouvez vos amis !</h1>


    <img src="../../images/footernutritium.png" id="LogosFooter" alt="LogosFooter" title="LogosFooter"> <!--logo Transnoise-->

<!--logo déconnexion-->
<img src="../../images/déconnexion2.png" id="imgdeco" alt="logo déconnexion" title="logo déconnexion" onmouseover="changerImage('survol')" onmouseout="changerImage('normal')" onclick="deconnexion()">

<footer>
  <div class="footer">
  <nav>
    <ul>
      <li><a href="CGU.php" id="ga" target="_blank">C.G.U</a></li>
      <li><a href="https://www.isep.fr/" id="ga" target="_blank">Nos investisseurs</a></li>
      <li><a href="faq.php" id="ga" target="_blank">Contact</a></li>
    </ul>
  </nav>
</div>
</footer>

<script src="../js/chat.js"> </script>
</body>
</html>