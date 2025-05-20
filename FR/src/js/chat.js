// Popup servant à la déconnexion
function deconnexion() {
  var result = confirm("Voulez-vous vraiment vous déconnecter?");
  if (result == true) {
    alert("Merci de votre visite");
    window.location.href = 'logout.php';
  }
}

// JS Hover bouton déconnexion
function changerImage(etat) {
  var img = document.getElementById("imgdeco");
  if (etat === "survol") {
    img.src = "../../images/déconnexion2-hover.png";
  } else {
    img.src = "../../images/déconnexion2.png";
  }
}

// Gestion dynamique de la hauteur du footer
var footerHeight = document.querySelector('.footer').offsetHeight;
var logosFooter  = document.getElementById('LogosFooter');
logosFooter.style.maxHeight = footerHeight + 'px';

var imgDeco = document.getElementById('imgdeco');
imgDeco.style.maxHeight = footerHeight + 'px';

document.addEventListener("DOMContentLoaded", function () {
  // 1) Bascule formulaires login/register
  var registerBtn     = document.getElementById("registerBtn");
  var loginBtn        = document.getElementById("loginBtn");
  var loginWrapper    = document.getElementById("loginWrapper");
  var registerWrapper = document.getElementById("registerWrapper");

  registerBtn.addEventListener("click", function () {
    loginWrapper.style.display    = "none";
    registerWrapper.style.display = "block";
  });
  loginBtn.addEventListener("click", function () {
    loginWrapper.style.display    = "block";
    registerWrapper.style.display = "none";
  });

  // 2) Login form
  var loginForm = document.getElementById("loginForm");
  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();
    var email = document.getElementById("loginEmail").value;
    var pw    = document.getElementById("loginPassword").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "login.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      try {
        var res = JSON.parse(this.responseText);
        if (res.status === "success") window.location.href = "../php/index.php";
        else alert(res.message || "Une erreur est survenue");
      } catch (err) {
        console.error("Login JSON parse error:", err, this.responseText);
      }
    };
    xhr.send("loginEmail=" + encodeURIComponent(email) +
        "&loginPassword=" + encodeURIComponent(pw));
  });

  // 3) Register form
  var registerForm = document.getElementById("registerForm");
  registerForm.addEventListener("submit", function (e) {
    e.preventDefault();
    var fn = document.getElementById("registerFirstName").value;
    var ln = document.getElementById("registerLastName").value;
    var em = document.getElementById("registerEmail");
    var pw = document.getElementById("registerPassword").value;
    var rp = document.getElementById("RepeatPassword").value;

    if (pw !== rp) {
      alert("Les mots de passe ne correspondent pas.");
      return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "register.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      try {
        var res = JSON.parse(this.responseText);
        if (res.status === "success") window.location.href = "../php/index.php";
        else if (res.status==="error" && res.message==="email_exists") {
          em.style.color = 'red';
          alert("Cet email est déjà utilisé.");
        } else {
          em.style.color = 'initial';
          alert(res.message||"Erreur lors de l'enregistrement");
        }
      } catch (err) {
        console.error("Register JSON parse error:", err, this.responseText);
      }
    };
    xhr.send(
        "registerFirstName=" + encodeURIComponent(fn) +
        "&registerLastName="  + encodeURIComponent(ln) +
        "&registerEmail="     + encodeURIComponent(em.value) +
        "&registerPassword="  + encodeURIComponent(pw) +
        "&RepeatPassword="    + encodeURIComponent(rp)
    );
  });

  // 4) Fil « Let's Chat ! » (sur la page concernée)
  var feed = document.getElementById("chat-feed");
  if (!feed) return;  // si pas sur la page chat, on arrête ici

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
        }).catch(console.error);
  }

  function sendMessage() {
    var inp  = document.getElementById('chat-input');
    var txt  = inp.value.trim();
    if (!txt) return;

    fetch('api/post_message.php', {
      method:  'POST',
      headers: {'Content-Type':'application/json'},
      body:    JSON.stringify({ content: txt })
    })
        .then(r => r.json())
        .then(res => {
          if (res.status==='success') {
            inp.value = '';
            loadFeed();
          } else {
            alert(res.message||'Erreur lors de l’envoi');
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
