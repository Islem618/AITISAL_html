// ─── Popup de déconnexion ─────────────────────────────────────────
function deconnexion() {
    if (confirm("Voulez-vous vraiment vous déconnecter?")) {
        alert("Merci de votre visite");
        window.location.href = 'logout.php';
    }
}

// ─── Hover sur le bouton déconnexion ─────────────────────────────
function changerImage(etat) {
    const img = document.getElementById("imgdeco");
    if (!img) return;
    img.src = etat === "survol"
        ? "../../images/déconnexion2-hover.png"
        : "../../images/déconnexion2.png";
}

// ─── Ajuster la hauteur des logos au footer ───────────────────────
const footerHeight = document.querySelector('.footer').offsetHeight;
document.getElementById('LogosFooter').style.maxHeight = footerHeight + 'px';
document.getElementById('imgdeco')     .style.maxHeight = footerHeight + 'px';

// ─── Popup déconnexion ───────────────────────────────────────────
function deconnexion() {
    if (confirm("Voulez-vous vraiment vous déconnecter ?")) {
        alert("Merci de votre visite");
        window.location.href = 'logout.php';
    }
}

// ─── Hover sur le bouton déconnexion ─────────────────────────────
function changerImage(etat) {
    const img = document.getElementById("imgdeco");
    if (!img) return;
    img.src = (etat === "survol")
        ? "../../images/déconnexion2-hover.png"
        : "../../images/déconnexion2.png";
}

// ────────────────────────────────────────────────────────────────────────────────────
// Au chargement de la page, on initialise chat + liste d’amis
// ────────────────────────────────────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", function() {
    var feed     = document.getElementById("chat-feed");
    var form     = document.getElementById("chat-form");
    var input    = document.getElementById("chat-input");
    var userList = document.getElementById("user-list");
    var me       = window.currentUserId || 0;

    if (!feed || !form || !input || !userList) {
        // Si ces éléments n’existent pas, on arrête
        return;
    }

    // ─── 1) Charger le mur public (flux)
    function loadFeed() {
        fetch('api/get_feed.php')
            .then(function(resp) { return resp.json(); })
            .then(function(posts) {
                // posts doit être un tableau d’objets { username, created_at, content }
                feed.innerHTML = "";
                posts.forEach(function(p) {
                    var div = document.createElement('div');
                    div.className = "post";
                    div.innerHTML = "<strong>" + p.username + "</strong> " +
                        "<small>" + p.created_at + "</small>" +
                        "<p>" + p.content + "</p>";
                    feed.appendChild(div);
                });
                // Scroll en bas
                feed.scrollTop = feed.scrollHeight;
            })
            .catch(function(err) {
                console.error("Erreur loadFeed :", err);
            });
    }

    // ─── 2) Envoyer un message au mur public
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        var texte = input.value.trim();
        if (!texte) return;
        var payload = { content: texte };
        fetch('api/post_message.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload)
        })
            .then(function(resp) { return resp.json(); })
            .then(function(res) {
                if (res.status === 'success') {
                    input.value = "";
                    loadFeed();
                } else {
                    alert(res.message || "Erreur envoi message");
                }
            })
            .catch(function(err) {
                console.error("Erreur sendMessage :", err);
            });
    });

    // ─── 3) Charger la liste des utilisateurs pour les ajouter en ami
    function loadUsers() {
        fetch('api/get_users.php')
            .then(function(resp) {
                // Vérifie la réponse brute pour débug si besoin
                return resp.json();
            })
            .then(function(users) {
                // S’assure que users est un tableau
                if (!Array.isArray(users)) {
                    console.error("loadUsers: réponse inattendue :", users);
                    userList.innerHTML = "<li>Erreur de chargement</li>";
                    return;
                }
                // Vide la liste
                userList.innerHTML = "";

                users.forEach(function(u) {
                    var li = document.createElement('li');
                    // On crée un bouton « Ajouter »
                    var btn = document.createElement('button');
                    btn.textContent = "Ajouter";
                    btn.dataset.id = u.id;

                    // Texte du li + bouton
                    li.textContent = u.username + " ";
                    li.appendChild(btn);
                    userList.appendChild(li);

                    // Événement clic sur « Ajouter »
                    btn.addEventListener("click", function() {
                        addFriend(u.id, btn);
                    });
                });
            })
            .catch(function(err) {
                console.error("Erreur loadUsers :", err);
                userList.innerHTML = "<li>Impossible de charger</li>";
            });
    }

    // ─── 4) Ajouter un ami
    function addFriend(friendId, btn) {
        // Désactive immédiatement le bouton pour éviter les doubles clics
        btn.disabled = true;

        fetch('api/add_friend.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ friend_id: friendId })
        })
            .then(function(resp) { return resp.json(); })
            .then(function(res) {
                if (res.status === 'success') {
                    // On recharge la liste des utilisateurs pour griser le bouton
                    loadUsers();
                    // (Optionnel) scroll automatique du chat, etc.
                    console.log("Ami ajouté, conversation ID =", res.conversation_id);
                } else {
                    alert(res.message || "Impossible d’ajouter cet ami");
                    // Si échec, on réactive le bouton
                    btn.disabled = false;
                }
            })
            .catch(function(err) {
                console.error("Erreur addFriend :", err);
                // En cas d’erreur réseau, on réactive le bouton
                btn.disabled = false;
            });
    }

    // ─── 5) Initialisation – charger le flux et la liste d’utilisateurs
    loadFeed();
    loadUsers();

    // Rafraîchissement automatique toutes les 2 secondes
    setInterval(function() {
        loadFeed();
    }, 2000);
});