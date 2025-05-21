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
    <title>Akatsuki - Nutritium</title>
    
    
  </head>

  <!--header gere la barre du haut de l'écran-->

  <header>
    <nav>
      <ul class="menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="chat.php">Let's Chat !</a></li>
        <li><a href="espaceuser.php">Profil</a></li>
      </ul>
    </nav>

  </header>
  <!--body gere le corp de la page-->
  <body>
    <!--le logo en haut à gauche-->
        <a href="index.php">
            <img src="../../images/logonutritium.png" id="Logo1" alt="Logo EchoKey" title="Logo EchoKey">
        </a>
    <?php
if(isset($_SESSION['user_id'])) {
  echo '<p class="welcome-message">Bienvenue ' . $_SESSION['prenom'] . ' !' . '</p>';}
  ?>
    <!--gere le texte au milieu de la page-->
    <div class="content">
      <h1>
        Nutritium
      </h1>
      <img src="../../images/homepic.jpg" alt="chanteur" height="300px" />
    </div>
    <div class="content-container">
      <div class="left">
        <h1 class="Nutritium">Apporter une solution durable <br /> A la précarité étudiante </h1>
        <p class="paragraphe">
            <br /> Chaque année, les files d’étudiants devant les banques alimentaires s’allongent. Ces scènes sont d’autant plus marquantes lorsque des mesures comme les repas à 1 € pour tous au CROUS sont rejetées. Face à ce constat alarmant, nous avons décidé de proposer une solution moderne et pérenne : Nutritium, une structure conçue pour répondre efficacement à ce problème grâce à des modèles de financement actuels et des partenariats stratégiques.
        </p>
    <br />
    <p class="paragraphe"> C’est avec ambition et détermination que nous portons le projet de Nutritium, une structure conçue pour aider un maximum de personnes à se nourrir dignement à un coût minimal, tout en répondant de manière durable à une problématique sociale urgente.</p>
<br />
        <p class="paragraphe"> Géographiquement basés à l’incubateur ISEP d’ Issy-Les-Moulineaux, notre démarche commencera à Paris et sa banlieue, avec l'ambition de nous étendre progressivement à l'échelle nationale d’ici 3 ans.</p><br />
      </div>
    </div>
    <div class="buton">
     
<?php
if(isset($_SESSION['user_id'])) {
  echo '<button class="cn" id="scrollButton"><a href="logout.php">Souhaitez vous vous déconnecter</a></button>';
} else {
  // Si l'utilisateur n'est pas connecté, affichez le bouton de connexion
  echo '<button class="cn" id="scrollButton"><a href="Connexion.php">Connectez Vous !</a></button>';
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
      <li><a href="CGU.php" id="ga" target="_blank">C.G.U</a></li>
      <li><a href="https://www.isep.fr/" id="ga" target="_blank">Nos investisseurs</a></li>
      <li><a href="faq.php" id="ga" target="_blank">Contact</a></li>
    </ul>
  </nav>
</div>
</footer>
<script src="../js/index.js"></script>
</body>
</html>
