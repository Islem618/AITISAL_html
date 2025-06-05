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

    <link rel="icon" type="image/x-icon" href="../../images/logoAITISAL.ico" />
    <title>AITISAL</title>
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
      <img src="../../images/logoaitisal.png" id="Logo1" alt="Logo EchoKey" title="Logo EchoKey">
  </a>
<div class="container"><!--barre de recherche-->
  <h2 style="text-align: center; font-size: 60.724px;">Ask us anything !</h2>  <!--titre question-->

<div class="wrapper"> <!-- Barre de recherche -->
  <div class="searchBar">
      <form id="searchForm">
        <input id="searchQueryInput" type="text" name="searchQueryInput" placeholder="Ask your question here" value="">
          <button id="searchQuerySubmit" type="submit" name="searchQuerySubmit">
            <img class="arrow-icon" id="hoverImage" src="../../images/arrow.png" width="30" height="30"/><!--Un élément Javascript s'occupe lui de détecter le survol de la souris et charge une image grisée de celle-ci.-->
          </button>
      </form>
  </div>
</div>




<h4 style="text-align: center;"><br>Or check the following questions:</h4> <!--questions génériques-->
    <div class="accordion">
      <div class="accordion-item">
        <button id="accordion-button-1" aria-expanded="false"><span class="accordion-title">How does the chat works ?</span><span class="icon" aria-hidden="true"></span></button>
        <div class="accordion-content">
          <p>It's easy. <br><br> Once your account is created, you can start using the AITISAL™ service and start talking from the page <a href="chat.php">Let's Chat !</a>
              <br><br>You can then start a conversation on the public wall, add a friend and start a private conversation with...<br></p>
        </div>
      </div>
      <div class="accordion-item">
        <button id="accordion-button-2" aria-expanded="false"><span class="accordion-title">How to create an AITISAL™ account?</span><span class="icon" aria-hidden="true"></span></button>
        <div class="accordion-content">
          <p>It's simple! When you arrive on the site, we'll offer you the option to create an account directly, or to log in. From there, you can enter your email address, first name, last name, and password, and you're ready to go!<br><br>
              Afterwards, you will have the opportunity to personalize your profile via the <a href="Profil.php">Profile</a> page.<br><br>
        </div>
      </div>
      <div class="accordion-item">
        <button id="accordion-button-3" aria-expanded="false"><span class="accordion-title">What is the added value of AITISAL™ ?</span><span class="icon" aria-hidden="true"></span></button>
        <div class="accordion-content">
            <p>At AITISAL, our mission is to break student isolation and create an authentic community. Through dedicated features (groups, events, resource sharing) and university partnerships, we offer a secure and dynamic space for exchange and collaboration.<br><br> Together, we strengthen student life and facilitate access to opportunities.</p>

        </div>
      </div>
      <div class="accordion-item">
        <button id="accordion-button-4" aria-expanded="false"><span class="accordion-title">How to customize your profile on AITISAL™ ?</span><span class="icon" aria-hidden="true"></span></button>
        <div class="accordion-content">
          <p>Well, thanks to the dedicated page! <br><br> On the Profile page, you can change your interests, your name, first name, date of birth, city, telephone number and finally your profile picture!!!
              <br><br>All this will also allow us to get to know you better and suggest friends who have more in common with you!
          </p>
        </div>
      </div>
      <div class="accordion-item">
        <button id="accordion-button-5" aria-expanded="false"><span class="accordion-title">Any other questions ?</span><span class="icon" aria-hidden="true"></span></button>
        <div class="accordion-content">
          <p>Do you have a question but haven't found the answer in this FAQ?<br><br>No problem!!!<br><br>Once logged into your user session, simply ask your question in the dedicated bar and an email will be sent to our AITISAL™ team.
            <br><br>From our AITISAL™ office, a member of our team will then be delighted to respond to your email and you will then receive a response to the email address provided when you registered.
            <br><br>P.S: Remember to check your spam... It would be a shame to miss the answer to a question when it is just a click away!!!</p>
        </div>
      </div>
    </div>
  </div>

  <img src="../../images/footeraitisal.png" id="LogosFooter" alt="LogosFooter" title="LogosFooter"> <!--logo Transnoise-->
  <li>
    <!--logo déconnexion-->
    <img src="../../images/déconnexion2.png" id="imgdeco" alt="logo déconnexion" title="logo déconnexion" onmouseover="changerImage('survol')" onmouseout="changerImage('normal')" onclick="deconnexion()">
  </li>
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

  <script src="../js/faq.js"></script>
  <script src="../js/notifications.js"></script>
</body>
</html>