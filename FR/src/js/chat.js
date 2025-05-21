// Popup servant à la déconnexion
function deconnexion() {
  if (confirm("Voulez-vous vraiment vous déconnecter?")) {
    alert("Merci de votre visite");
    window.location.href = 'logout.php';
  }
}

// JS Hover bouton déconnexion
function changerImage(etat) {
  var img = document.getElementById("imgdeco");
  if (!img) return;
  img.src = etat === "survol"
      ? "../../images/déconnexion2-hover.png"
      : "../../images/déconnexion2.png";
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

document.addEventListener("DOMContentLoaded", function () {
  // Si on n'est pas sur la page chat, on ne fait rien de plus
  var feed = document.getElementById("chat-feed");
  if (!feed) return;

  // Charge les messages existants
  function loadFeed() {
    fetch('api/get_feed.php')
        .then(r => r.json())
        .then(posts => {
          feed.innerHTML = posts.map(p => `
          <div class="post">
            <strong>${p.username}</strong>
            <small>${p.created_at}</small>
            <p>${p.content}</p>
          </div>
        `).join('');
          feed.scrollTop = feed.scrollHeight;
        })
        .catch(console.error);
  }

  // Envoie un nouveau message
  function sendMessage() {
    var inp = document.getElementById('chat-input');
    var txt = inp.value.trim();
    if (!txt) return;
    fetch('api/post_message.php', {
      method:  'POST',
      headers: {'Content-Type':'application/json'},
      body:    JSON.stringify({ content: txt })
    })
        .then(r => r.json())
        .then(res => {
          if (res.status === 'success') {
            inp.value = '';
            loadFeed();
          } else {
            alert(res.message || 'Erreur lors de l’envoi');
          }
        })
        .catch(console.error);
  }

  loadFeed();
  document.getElementById("chat-form")
      .addEventListener("submit", function(e){
        e.preventDefault();
        sendMessage();
      });
});
