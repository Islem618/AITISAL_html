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

document.addEventListener('DOMContentLoaded', () => {
    const feed        = document.getElementById('chat-feed');
    const form        = document.getElementById('chat-form');
    const input       = document.getElementById('chat-input');
    const userList    = document.getElementById('user-list');
    const convList    = document.getElementById('conversation-list');
    const recoList    = document.getElementById('recommendation-list');
    const me          = window.currentUserId || 0;
    let   currentConv = null;

    // Si on n'est pas sur chat.php, on quitte
    if (!feed || !form || !input || !userList || !convList || !recoList) return;

    // ─── Bouton “← Chat public” ─────────────────────────────────────
    const returnBtn = document.createElement('button');
    returnBtn.textContent      = '← Chat public';
    returnBtn.style.display    = 'none';
    returnBtn.style.marginBottom = '10px';
    returnBtn.addEventListener('click', () => {
        currentConv = null;
        returnBtn.style.display = 'none';
        convList.querySelectorAll('li').forEach(x => x.classList.remove('active'));
        loadFeed();
    });
    feed.parentNode.insertBefore(returnBtn, feed);

    // ─── 1) Charger la liste des conversations privées ───────────────
    function loadConversations() {
        fetch('../php/api/get_conversations.php')
            .then(resp => resp.json())
            .then(convs => {
                convList.innerHTML = '';

                if (!Array.isArray(convs) || convs.length === 0) {
                    const li = document.createElement('li');
                    li.textContent     = 'Vous n’avez pas démarré de conversation privée.';
                    li.style.fontStyle = 'italic';
                    li.style.color     = '#555';
                    convList.appendChild(li);
                    return;
                }

                convs.forEach(c => {
                    const friendName = `${c.friend_prenom} ${c.friend_nom}`;
                    const li = document.createElement('li');
                    li.textContent  = `Chat privé ${friendName}`;
                    li.dataset.id   = c.conversation_id;

                    if (c.conversation_id === currentConv) {
                        li.classList.add('active');
                    }
                    li.addEventListener('click', () => {
                        currentConv = c.conversation_id;
                        returnBtn.style.display = 'block';
                        convList.querySelectorAll('li').forEach(x => x.classList.remove('active'));
                        li.classList.add('active');
                        loadMessages();
                    });
                    convList.appendChild(li);
                });

                if (!currentConv && convs.length) {
                    const firstLi = convList.querySelector('li[data-id]');
                    if (firstLi) firstLi.click();
                }
            })
            .catch(err => {
                console.error('Erreur loadConversations :', err);
                convList.innerHTML = '<li>Impossible de charger les conversations</li>';
            });
    }

    // ─── 2) Charger les messages d’une conversation privée ────────────
    function loadMessages() {
        if (!currentConv) return;
        fetch(`../php/api/get_messages.php?conversation_id=${currentConv}`)
            .then(resp => resp.json())
            .then(msgs => {
                feed.innerHTML = '';
                msgs.forEach(m => {
                    const div = document.createElement('div');
                    div.className = 'message ' + (m.from_id === me ? 'me' : 'them');
                    div.innerHTML = `
                        <strong>${m.prenom} ${m.nom}</strong>
                        <small>${m.created_at}</small>
                        <p>${m.content}</p>
                    `;
                    feed.appendChild(div);
                });
                feed.scrollTop = feed.scrollHeight;
            })
            .catch(err => {
                console.error('Erreur loadMessages :', err);
                feed.innerHTML = '<p>Impossible de charger les messages privés.</p>';
            });
    }

    // ─── 3) Charger le chat public (flux) ───────────────────────────
    function loadFeed() {
        fetch('../php/api/get_feed.php')
            .then(resp => resp.json())
            .then(posts => {
                feed.innerHTML = '';
                posts.forEach(p => {
                    const div = document.createElement('div');
                    div.className = 'post';
                    div.innerHTML = `
                        <strong>${p.username}</strong>
                        <small>${p.created_at}</small>
                        <p>${p.content}</p>
                    `;
                    feed.appendChild(div);
                });
                feed.scrollTop = feed.scrollHeight;
            })
            .catch(err => {
                console.error('Erreur loadFeed :', err);
                feed.innerHTML = '<p>Impossible de charger le mur public.</p>';
            });
    }

    // ─── 4) Envoi d’un message (privé ou public) ─────────────────────
    form.addEventListener('submit', e => {
        e.preventDefault();
        const txt = input.value.trim();
        if (!txt) return;
        const payload = { content: txt };
        if (currentConv) payload.conversation_id = currentConv;

        fetch('../php/api/post_message.php', {
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
            .catch(err => console.error('Erreur sendMessage :', err));
    });

    // ─── 5) Charger la liste des utilisateurs (span clickable + bouton Ajouter) ─
    function loadUsers() {
        fetch('../php/api/get_users.php')
            .then(resp => resp.json())
            .then(users => {
                if (!Array.isArray(users)) {
                    console.error('loadUsers : réponse inattendue', users);
                    userList.innerHTML = '<li>Erreur de chargement</li>';
                    return;
                }
                userList.innerHTML = '';
                users.forEach(u => {
                    const li   = document.createElement('li');
                    const span = document.createElement('span');
                    span.textContent        = u.username;
                    span.style.cursor       = 'pointer';
                    span.style.textDecoration = 'underline';
                    span.style.color        = '#004';
                    span.addEventListener('click', () => openConversation(u.id));

                    const btn = document.createElement('button');
                    btn.textContent    = 'Ajouter';
                    btn.dataset.id     = u.id;
                    btn.style.marginLeft = '6px';
                    btn.addEventListener('click', () => addFriend(u.id, btn));

                    li.appendChild(span);
                    li.appendChild(document.createTextNode(' '));
                    li.appendChild(btn);
                    userList.appendChild(li);
                });
            })
            .catch(err => {
                console.error('Erreur loadUsers :', err);
                userList.innerHTML = '<li>Impossible de charger les utilisateurs.</li>';
            });
    }

    // ─── 6) Créer l’amitié + la conversation privée d’emblée ─────────
    function addFriend(friendId, btn) {
        btn.disabled = true;
        fetch('../php/api/add_friend.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ friend_id: friendId })
        })
            .then(resp => resp.json())
            .then(res => {
                if (res.status === 'success') {
                    loadUsers();
                    currentConv = res.conversation_id;
                    returnBtn.style.display = 'block';
                    loadConversations();
                    setTimeout(loadMessages, 100);
                } else {
                    alert(res.message || 'Impossible d’ajouter cet ami');
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error('Erreur addFriend :', err);
                btn.disabled = false;
            });
    }

    // ─── 7) Ouvrir (ou créer) la conversation privée via “span click” ─
    function openConversation(friendId) {
        fetch(`../php/api/get_conversation.php?friend_id=${friendId}`)
            .then(resp => resp.json())
            .then(data => {
                const cid = data.conversation_id || 0;
                if (cid > 0) {
                    currentConv = cid;
                    returnBtn.style.display = 'block';
                    loadConversations();
                    setTimeout(loadMessages, 100);
                } else {
                    fetch('../php/api/create_conversation.php', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body:    JSON.stringify({ friend_id: friendId })
                    })
                        .then(resp => {
                            if (!resp.ok) {
                                return resp.json().then(errObj => {
                                    throw new Error(errObj.message || 'Erreur interne');
                                });
                            }
                            return resp.json();
                        })
                        .then(data2 => {
                            if (data2.status === 'success' && data2.conversation_id) {
                                currentConv = data2.conversation_id;
                                returnBtn.style.display = 'block';
                                loadConversations();
                                setTimeout(loadMessages, 100);
                            } else {
                                alert(data2.message || 'Impossible d’ouvrir la conversation privée.');
                            }
                        })
                        .catch(err => {
                            console.error('Erreur openConversation (création) :', err);
                            alert(err.message || 'Impossible d’ouvrir la conversation privée.');
                        });
                }
            })
            .catch(err => {
                console.error('Erreur openConversation (vérif) :', err);
                alert('Impossible d’ouvrir la conversation privée.');
            });
    }

    // ─── 9) Charger les suggestions d’amis (friend-of-friend) ─────────
    function loadRecommendations() {
        fetch('../php/api/get_recommendations.php')
            .then(resp => resp.json())
            .then(rows => {
                recoList.innerHTML = '';
                if (!Array.isArray(rows) || rows.length === 0) {
                    const li = document.createElement('li');
                    li.textContent     = 'Aucune suggestion pour l’instant.';
                    li.style.fontStyle = 'italic';
                    recoList.appendChild(li);
                    return;
                }
                rows.forEach(r => {
                    const li = document.createElement('li');
                    li.textContent = `${r.suggestion_prenom} ${r.suggestion_nom} (${r.mutual_count} ami(s) en commun)`;
                    recoList.appendChild(li);
                });
            })
            .catch(err => {
                console.error('Erreur loadRecommendations :', err);
                recoList.innerHTML = '<li>Impossible de charger les suggestions.</li>';
            });
    }

    // ─── 10) Initialisation ───────────────────────────────────────────
    loadConversations();
    loadUsers();
    loadRecommendations();
    loadFeed();

    // Rafraîchissement automatique
    setInterval(() => {
        if (currentConv) loadMessages();
        else            loadFeed();
    }, 2000);

    // Rafraîchir aussi les suggestions toutes les 30s
    setInterval(() => {
        loadRecommendations();
    }, 30000);
});