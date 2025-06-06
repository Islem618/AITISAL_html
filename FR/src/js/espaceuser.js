//footer
// Popup servant à la déconnexion
function deconnexion() {
  var result = confirm("Voulez-vous vraiment vous déconnecter?");
  if (result == true) {
    alert("Merci de votre visite");
    
    // Effectuer une redirection vers le script PHP de déconnexion
    window.location.href = 'logout.php';
  } else {
    // On ferme juste la popup puisqu'on ne se déconnecte pas en cliquant sur annuler
  }
}


// JS Hover bouton déconnexion
function changerImage(etat) {
  var img = document.getElementById("imgdeco");
  if (etat === "survol") {
    img.src = "../../images/hover-signoutaitisal.png"; // Chemin vers l'image au survol
  } else {
    img.src = "../../images/signoutaitisal.png"; // Chemin vers l'image normale
  }
}


// Récupérer la hauteur du footer
var footerHeight = document.querySelector('.footer').offsetHeight;

// Appliquer la hauteur du footer comme max-height au LogosFooter
var logosFooter = document.getElementById('LogosFooter');
logosFooter.style.maxHeight = footerHeight + 'px';

// Récupérer la hauteur du footer
var footerHeight = document.querySelector('.footer').offsetHeight;

// Appliquer la hauteur du footer comme max-height à l'élément avec l'id "imgdeco"
var imgDeco = document.getElementById('imgdeco');
imgDeco.style.maxHeight = footerHeight + 'px';

