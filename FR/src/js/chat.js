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

// ─── DOMContentLoaded : chat + ajout d’amis ──────────────────────
document.addEventListener("DOMContentLoaded", () => {
    const feed     = document.getElementById("chat-feed");
    const form     = document.getElementById("chat-form");
    const input    = document.getElementById("chat-input");
    const userList = document.getElementById("user-list");
    const me       = window.currentUserId;   // <-- Ajout nécessaire
    let   currentConv = null;                // <-- Ajout nécessaire

    if (!feed) return; // on n'est pas sur la page chat

    // ─── 1) Récupérer la liste des conversations ──────────────────
    function loadConversations() {
        fetch('api/get_conversations.php')
            .then(r => r.json())
            .then(convs => {
                if (convs.length && currentConv === null) {
                    currentConv = convs[0].conversation_id;
                }
                loadMessages();
            })
            .catch(console.error);
    }

    // ─── 2) Charger les messages de la conversation active ────────
    function loadMessages() {
        if (!currentConv) return;
        fetch(`api/get_messages.php?conversation_id=${currentConv}`)
            .then(r => r.json())
            .then(msgs => {
                feed.innerHTML = msgs.map(m => `
          <div class="message ${m.from_id === me ? 'me' : 'them'}">
            <strong>${m.prenom} ${m.nom}</strong>
            <small>${m.created_at}</small>
            <p>${m.content}</p>
          </div>
        `).join('');
                feed.scrollTop = feed.scrollHeight;
            })
            .catch(console.error);
    }

    // ─── 3) Envoyer un message (privé si conv, sinon mur public) ──
    function sendMessage() {
        const txt = input.value.trim();
        if (!txt) return;

        const payload = { content: txt };
        if (currentConv) payload.conversation_id = currentConv; // <-- Ajout

        fetch('api/post_message.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload)
        })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') {
                    input.value = '';
                    if (currentConv) loadMessages();
                    else            loadFeed();
                } else {
                    alert(res.message || 'Erreur lors de l’envoi');
                }
            })
            .catch(console.error);
    }

    // ─── 4) Ancien mur public (flux) ───────────────────────────────
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

    // ─── 5) Liste des utilisateurs + ajout d’amis ────────────────
    function loadUsers() {
        fetch('api/get_users.php')
            .then(r => r.json())
            .then(users => {
                userList.innerHTML = '';
                users.forEach(u => {
                    const li = document.createElement('li');
                    li.innerHTML = `
            ${u.username}
            <button class="btn-add" data-id="${u.id}">Ajouter</button>
          `;
                    userList.appendChild(li);
                });
                userList.querySelectorAll('.btn-add').forEach(btn => {
                    btn.addEventListener('click', () => addFriend(btn.dataset.id));
                });
            })
            .catch(console.error);
    }

    function addFriend(friendId) {
        fetch('api/add_friend.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ friend_id: friendId })
        })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') loadUsers();
                else                          alert(res.message || "Impossible d’ajouter cet ami");
            })
            .catch(console.error);
    }

    // ─── Initialisation + polling auto every 2s ───────────────────
    loadConversations();
    // si tu veux conserver le mur public en plus :
    // loadFeed();

    form.addEventListener("submit", e => {
        e.preventDefault();
        sendMessage();
    });

    loadUsers();
    setInterval(() => {
        if (currentConv) loadMessages();
        else            loadFeed();
    }, 2000);
});
