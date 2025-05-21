// ─── Popup de déconnexion ─────────────────────────────────────────
function deconnexion() {
    if (confirm("Voulez-vous vraiment vous déconnecter?")) {
        alert("Merci de votre visite");
        window.location.href = 'logout.php';
    }
}

// ─── Hover sur le bouton déconnexion ─────────────────────────────
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

// ─── DOMContentLoaded : chat + ajout d’amis ──────────────────────
document.addEventListener("DOMContentLoaded", function () {
    // On ne poursuit que si on est sur la page chat
    var feed = document.getElementById("chat-feed");
    if (!feed) return;

    // ─── 1) Fil de discussion ──────────────────────────────────────
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

    // ─── 2) Liste des utilisateurs & ajout d’amis ────────────────
    function loadUsers() {
        fetch('api/get_users.php')
            .then(r => r.json())
            .then(users => {
                var ul = document.getElementById('user-list');
                ul.innerHTML = '';
                users.forEach(u => {
                    var li = document.createElement('li');
                    li.innerHTML = `
            ${u.username}
            <button class="btn-add" data-id="${u.id}">Ajouter</button>
          `;
                    ul.appendChild(li);
                });
                // lier les boutons
                ul.querySelectorAll('.btn-add').forEach(btn => {
                    btn.addEventListener('click', function(){
                        addFriend(this.dataset.id);
                    });
                });
            })
            .catch(console.error);
    }

    function addFriend(friendId) {
        fetch('api/add_friend.php', {
            method:  'POST',
            headers: {'Content-Type':'application/json'},
            body:    JSON.stringify({ friend_id: friendId })
        })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') {
                    loadUsers();  // rafraîchir pour masquer l’ami ajouté
                } else {
                    alert(res.message || "Impossible d’ajouter cet ami");
                }
            })
            .catch(console.error);
    }

    // appel initial
    loadUsers();
});
