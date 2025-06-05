<?php
session_start(); // Démarre la session au début du script
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link href="../css/index.css" rel="stylesheet" />
    <script src="../js/jquery.min.js"></script>
   
    <link rel="icon" type="image/x-icon" href="../../images/logonutritium%20-%20Copie.ico" />
    <title>AITISAL</title>
    
    
  </head>

  <!--header gere la barre du haut de l'écran-->

  <header>
    <nav>
      <ul class="menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="chat.php">Let's Chat !</a></li>
        <li><a href="espaceuser.php">Profile</a></li>
      </ul>
    </nav>

  </header>
  <!--body gere le corp de la page-->
  <body>
    <!--le logo en haut à gauche-->
        <a href="index.php">
            <img src="../../images/logonutritium.png" id="Logo1" alt="Logo EchoKey" title="Logo EchoKey">
        </a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Pastille de notification -->
        <span id="notification-badge">0</span>

        <!-- Panneau de notifications -->
        <section id="notification-box">
            <h3>Notifications</h3>
            <ul id="notification-panel"></ul>
        </section>
    <?php endif; ?>

    <?php
if(isset($_SESSION['user_id'])) {
  echo '<p class="welcome-message">Welcome ' . $_SESSION['prenom'] . ' !' . '</p>';}
  ?>
    <!--gere le texte au milieu de la page-->
    <div class="content">
      <h1>
        AITISAL
      </h1>
      <img src="../../images/homepic.jpg" alt="chanteur" height="300px" />
    </div>
    <div class="content-container">
      <div class="left">
        <h1 class="Nutritium">Bring a durable solution <br /> To social isolation! </h1>
        <p class="paragraphe">
            <br /> Every year, student social isolation increases, but AITISAL, our new student social network, offers an effective solution thanks to its back-to-basics approach: no ads, no disruptions, just you, your friends, and the world.
        </p>
    <br />
    <p class="paragraphe"> It is with ambition and determination that we are carrying out the AITISAL project, a structure designed to help as many people as possible come together for free, while responding sustainably to an urgent social problem.</p>
<br />
        <p class="paragraphe"> Geographically based at the ISEP incubator in Issy-Les-Moulineaux, our approach will begin in these premises, with the ambition of finding larger premises and expanding within 3 years.</p><br />
      </div>
    </div>
    <div class="buton">
     
<?php
if(isset($_SESSION['user_id'])) {
  echo '<button class="cn" id="scrollButton"><a href="logout.php">Would you like to log out?</a></button>';
} else {
  // Si l'utilisateur n'est pas connecté, affichez le bouton de connexion
  echo '<button class="cn" id="scrollButton"><a href="Connexion.php">Sign In !</a></button>';
}
?>
      </button>
    </div>

  <!--footer gere le bas de page-->
  <img src="../../images/footernutritium.png" id="LogosFooter" alt="LogosFooter" title="LogosFooter"> <!--logo Transnoise-->

<footer>
  <div class="footer">
  <nav>
    <ul>
      <li><a href="CGU.php" id="ga" target="_blank">G.C.U</a></li>
      <li><a href="https://www.isep.fr/" id="ga" target="_blank">Our investors</a></li>
      <li><a href="faq.php" id="ga" target="_blank">Contact</a></li>
    </ul>
  </nav>
</div>
</footer>
<script src="../js/index.js"></script>
    <?php if (isset($_SESSION['user_id'])): ?>
        <script>
            window.currentUserId = <?= (int)($_SESSION['user_id'] ?? $_SESSION['id_User']) ?>;
        </script>
        <script src="../js/notifications.js"></script>
    <?php endif; ?>

  </body>
</html>
