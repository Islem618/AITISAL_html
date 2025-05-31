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

document.addEventListener("DOMContentLoaded", () => {
    const feed       = document.getElementById("chat-feed");
    const form       = document.getElementById("chat-form");
    const input      = document.getElementById("chat-input");
    const userList   = document.getElementById("user-list");
    const convList   = document.getElementById("conversation-list");
    const me         = window.currentUserId || 0;
    let   currentConv = null;

    // Si on n’est pas sur chat.php, on sort
    if (!feed || !form || !input || !userList || !convList) {
        return;
    }

    // 1) Charger la liste des conversations privées
    function loadConversations() {
        fetch('api/get_conversations.php')
            .then(resp => resp.json())
            .then(convs => {
                convList.innerHTML = '';
                convs.forEach(c => {
                    const li = document.createElement('li');
                    li.textContent = `Conversation #${c.conversation_id}`;
                    li.dataset.id = c.conversation_id;
                    if (c.conversation_id === currentConv) {
                        li.classList.add('active');
                    }
                    li.addEventListener('click', () => {
                        currentConv = c.conversation_id;
                        // mise à jour visuelle
                        convList.querySelectorAll('li').forEach(x => x.classList.remove('active'));
                        li.classList.add('active');
                        loadMessages();
                    });
                    convList.appendChild(li);
                });
                // si aucune conv n’est sélectionnée, on clique la première
                if (!currentConv && convs.length) {
                    convList.querySelector('li').click();
                }
            })
            .catch(err => console.error("Erreur loadConversations :", err));
    }

    // 2) Charger les messages de la conversation active
    function loadMessages() {
        if (!currentConv) return;
        fetch(`api/get_messages.php?conversation_id=${currentConv}`)
            .then(resp => resp.json())
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
            .catch(err => console.error("Erreur loadMessages :", err));
    }

    // 3) Charger le mur public (flux) si pas de conversation active
    function loadFeed() {
        fetch('api/get_feed.php')
            .then(resp => resp.json())
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
            .catch(err => console.error("Erreur loadFeed :", err));
    }

    // 4) Envoi de message (dans conversation active ou sur le feed public)
    form.addEventListener('submit', e => {
        e.preventDefault();
        const txt = input.value.trim();
        if (!txt) return;

        const payload = { content: txt };
        if (currentConv) {
            payload.conversation_id = currentConv;
        }
        fetch('api/post_message.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload)
        })
            .then(resp => resp.json())
            .then(res => {
                if (res.status === 'success') {
                    input.value = '';
                    if (currentConv) loadMessages();
                    else            loadFeed();
                } else {
                    alert(res.message || 'Erreur lors de l’envoi');
                }
            })
            .catch(err => console.error("Erreur sendMessage :", err));
    });

    // 5) Charger la liste des utilisateurs pour ajouter en ami
    function loadUsers() {
        fetch('api/get_users.php')
            .then(resp => resp.json())
            .then(users => {
                if (!Array.isArray(users)) {
                    console.error("loadUsers : réponse inattendue", users);
                    userList.innerHTML = '<li>Erreur de chargement</li>';
                    return;
                }
                userList.innerHTML = '';
                users.forEach(u => {
                    const li = document.createElement('li');
                    li.textContent = u.username + ' ';
                    const btn = document.createElement('button');
                    btn.textContent = 'Ajouter';
                    btn.dataset.id = u.id;
                    btn.addEventListener('click', () => addFriend(u.id, btn));
                    li.appendChild(btn);
                    userList.appendChild(li);
                });
            })
            .catch(err => {
                console.error("Erreur loadUsers :", err);
                userList.innerHTML = '<li>Impossible de charger</li>';
            });
    }

    // 6) Ajouter un ami → crée friendship + conversation privée
    function addFriend(friendId, btn) {
        btn.disabled = true;
        fetch('api/add_friend.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ friend_id: friendId })
        })
            .then(resp => resp.json())
            .then(res => {
                if (res.status === 'success') {
                    loadUsers();  // rafraîchir la liste (bouton grisé)
                    const newCid = res.conversation_id;
                    if (newCid) {
                        currentConv = newCid;
                        loadConversations();
                        setTimeout(loadMessages, 100);
                    }
                } else {
                    alert(res.message || 'Impossible d’ajouter cet ami');
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error("Erreur addFriend :", err);
                btn.disabled = false;
            });
    }

    // 7) Initialisation
    loadConversations(); // Charge / affiche immédiatement les conversations
    loadUsers();         // Charge la liste des utilisateurs
    loadFeed();          // Affiche par défaut le feed public

    // rafraîchissement automatique toutes les 2 s
    setInterval(() => {
        if (currentConv) loadMessages();
        else            loadFeed();
    }, 2000);
});