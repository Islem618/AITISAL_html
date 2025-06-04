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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../css/faq.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/normalize.css">
    <script src="../js/jquery.min.js"></script>

    <link rel="icon" type="image/x-icon" href="../../images/logonutritium%20-%20Copie.ico" />
    <title>Akatsuki - Nutritium</title>
</head>

<body>
  <header>
    <nav>
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="chat.php">Let's Chat !</a></li>
            <li><a href="espaceuser.php">Profil</a></li>
        </ul>
    </nav>

    </header>
  <!-- Pastille notification (en haut à droite) -->
  <span id="notification-badge">
  0
</span>

  <!-- Panneau des notifications (au-dessus du contenu, masqué par défaut) -->
  <section id="notification-box">
      <h3>Notifications</h3>
      <ul id="notification-panel"></ul>
  </section>

  <!--le logo en haut à gauche-->
  <a href="index.php">
      <img src="../../images/logonutritium.png" id="Logo1" alt="Logo EchoKey" title="Logo EchoKey">
  </a>
<div class="container"><!--barre de recherche-->
  <h2 style="text-align: center; font-size: 60.724px;">Posez votre question:</h2>  <!--titre question-->

<div class="wrapper"> <!-- Barre de recherche -->
  <div class="searchBar">
      <form id="searchForm">
        <input id="searchQueryInput" type="text" name="searchQueryInput" placeholder="Posez votre question ici" value="">
          <button id="searchQuerySubmit" type="submit" name="searchQuerySubmit">
            <img class="arrow-icon" id="hoverImage" src="../../images/arrow.png" width="30" height="30"/><!--Un élément Javascript s'occupe lui de détecter le survol de la souris et charge une image grisée de celle-ci.-->
          </button>
      </form>
  </div>
</div>




<h4 style="text-align: center;"><br>Ou consultez les questions suivantes:</h4> <!--questions génériques-->
    <div class="accordion">
      <div class="accordion-item">
        <button id="accordion-button-1" aria-expanded="false"><span class="accordion-title">Comment fonctionne la commande de panier ?</span><span class="icon" aria-hidden="true"></span></button>
        <div class="accordion-content">
          <p>C'est très simple. <br><br> Une fois votre compte créé, vous pouvez commencer à utiliser le service Nutritium™ et réserver un panier depuis la page <a href="chat.php">Commander un panier</a>.
              <br><br>Sous réserve des stocks disponibles, un panier sera alors réservé chez le partenaire que vous aurez choisi, un qr code vous sera alors généré et vous pourrez aller récupérer et payer votre panier !<br></p>
        </div>
      </div>
      <div class="accordion-item">
        <button id="accordion-button-2" aria-expanded="false"><span class="accordion-title">De quoi est composé le panier ?</span><span class="icon" aria-hidden="true"></span></button>
        <div class="accordion-content">
          <p>A l'aide de nos nutritionnistes, nous avons constitué un panier alimentaire vous permettant de vous alimenter une semaine tout en restant sur un budget étudiant.<br><br>
          En effet, les paniers Nutritium™ ont été conçus avec soin pour garantir une composition variée tout en restant accessibles, sains et équilibrés.<br><br>
              D'ici peu, plusieurs nouveaux types de paniers seront mis à disposition, permettant tout type d’alimentation, en laissant l’utilisateur choisir le type de panier lui convenant : basique, végétarien, halal, kacher, végan. </p>
        </div>
      </div>
      <div class="accordion-item">
        <button id="accordion-button-3" aria-expanded="false"><span class="accordion-title">Quel est la plus-value de Nutritium™ ?</span><span class="icon" aria-hidden="true"></span></button>
        <div class="accordion-content">
          <p>C'est assez trivial à vrai dire !<br><br> Chez Nutritium™, nous avons vocation à rendre service sur le long terme, et d'être une entreprise rentable.
            <br><br>Pour ce faire, nous ne vous proposons pas des produits de basse qualité ou les invendus des partenaires mais bien un panier alimentaire complet de produits frais à moindre coût pour les étudiants en situation de précarité.
            <br><br>Enfin, grâce à vous et nos partenaires, nous pouvons vous proposer ce service original et innovant.</p>
        </div>
      </div>
      <div class="accordion-item">
        <button id="accordion-button-4" aria-expanded="false"><span class="accordion-title">Qui peut utiliser Nutritium™ ?</span><span class="icon" aria-hidden="true"></span></button>
        <div class="accordion-content">
          <p>Tout le monde ! A une différence près...<br><br> Nutritium™ est d'abord un service visant les étudiants en situation de précarité.
              <br><br>Ainsi, lors de la récupération du panier, vous devrez présenter votre carte étudiant afin de bénéficier de notre tarif préférentiel de 40€le panier.
            <br><br> Si vous n'êtes PAS étudiant mais que vous souhaitez quand même bénéficier de Nutritium™, c'est possible pour la modique somme de 50€ !!!
          </p>
        </div>
      </div>
      <div class="accordion-item">
        <button id="accordion-button-5" aria-expanded="false"><span class="accordion-title">Autre question ?</span><span class="icon" aria-hidden="true"></span></button>
        <div class="accordion-content">
          <p>Vous avez une question mais vous n'avez pas trouvé la réponse dans cette FAQ?<br><br>Pas de problème!!!<br><br>Connecté à votre session utilisateur, vous n'avez qu'à poser votre question dans la barre dédiée et un mail sera envoyé à notre équipe Nutritium™.
            <br><br>De nos bureaux Nutritium™, un membre de notre équipe se fera alors l'immense plaisir de répondre à votre mail et vous recevrez alors une réponse sur la boite mail communiquée lors de votre inscription.
            <br><br>P.S: Pensez bien à verifier vos spam... Il serait dommage de passer à côté de la réponse à une question alors que celle-ci se trouve à portée de clic!!!</p>
        </div>
      </div>
    </div>
  </div>

  <img src="../../images/footernutritium.png" id="LogosFooter" alt="LogosFooter" title="LogosFooter"> <!--logo Transnoise-->
  <li>
    <!--logo déconnexion-->
    <img src="../../images/déconnexion2.png" id="imgdeco" alt="logo déconnexion" title="logo déconnexion" onmouseover="changerImage('survol')" onmouseout="changerImage('normal')" onclick="deconnexion()">
  </li>
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

  <script src="../js/faq.js"></script>
  <script src="../js/notifications.js"></script>
</body>
</html>