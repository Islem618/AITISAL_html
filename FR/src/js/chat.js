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
// ─── Popup de déconnexion ─────────────────────────────────────────
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


// ─── Code principal après chargement du DOM ───────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const feed     = document.getElementById('chat-feed');
    const form     = document.getElementById('chat-form');
    const input    = document.getElementById('chat-input');
    const userList = document.getElementById('friend-candidates-list');
    const convList = document.getElementById('conversation-list');
    const me       = window.currentUserId || 0;
    let   currentConv = null;

    // Si on n’est pas sur chat.php, on quitte
    if (!feed || !form || !input || !userList || !convList) return;

    // ─── Bouton “← Chat public” ─────────────────────────────────────
    const returnBtn = document.createElement('button');
    returnBtn.textContent        = '← Chat public';
    returnBtn.className          = 'btn-public';
    returnBtn.style.display      = 'none';
    returnBtn.style.marginBottom = '10px';
    returnBtn.addEventListener('click', () => {
        currentConv = null;
        returnBtn.style.display = 'none';
        convList.querySelectorAll('li').forEach(x => x.classList.remove('active'));
        loadFeed();
    });
    // On insère ce bouton au-dessus de la zone “feed”
    feed.parentNode.insertBefore(returnBtn, feed);

    // ─── 1) Charger les conversations privées ─────────────────────────
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
                    li.textContent    = `Chat privé ${friendName}`;
                    li.dataset.id     = c.conversation_id;
                    // Si cette conversation est déjà affichée, on la marque “active”
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

                // Si aucune conversation n’est sélectionnée, on clique sur la première
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

    // ─── 2) Charger les messages d’une conversation privée ─────────────
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

    // ─── 3) Charger le chat public (mur) ───────────────────────────────
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

    // ─── 4) Envoi d’un message (privé ou public) ───────────────────────
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

    // ─── 5) Créer une amitié + conversation privée ─────────────────────
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
                    // Recharger la liste fusionnée
                    buildFriendCandidatesList();
                    // Ouvrir automatiquement la nouvelle conversation
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

    // ─── 6) Ouvrir (ou créer) la conversation privée via un clic ───────
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
                    // Créer la conversation si elle n’existe pas encore
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
                            console.error('Erreur openConversation :', err);
                            alert(err.message || 'Impossible d’ouvrir la conversation privée.');
                        });
                }
            })
            .catch(err => {
                console.error('Erreur openConversation :', err);
                alert('Impossible d’ouvrir la conversation privée.');
            });
    }

    // ─── 7) Construire la liste fusionnée “Amis potentiels” ─────────────
    // a) Récupère get_recommendations.php → [ { suggestion_id, suggestion_prenom, suggestion_nom, mutual_count, interest_count }, … ]
    // b) Récupère get_users.php        → [ { id, username }, … ]
    // c) Fusionne et affiche :
    //    • d’abord, chaque suggestion (suggestion-card)
    //    • puis, chaque autre utilisateur restant (normal-card)
    function buildFriendCandidatesList() {
        fetch('../php/api/get_recommendations.php')
            .then(res => res.json())
            .then(recoRows => {
                // 1) Construire le mapping des suggestions
                const recoMap = {};
                if (Array.isArray(recoRows)) {
                    recoRows.forEach(r => {
                        // On s’attend à : r.suggestion_id, r.suggestion_prenom, r.suggestion_nom, r.mutual_count, r.interest_count
                        recoMap[r.suggestion_id] = {
                            prenom:   r.suggestion_prenom,
                            nom:      r.suggestion_nom,
                            mutual:   r.mutual_count,
                            interest: r.interest_count
                        };
                    });
                }

                // 2) Récupérer ensuite la liste épurée des utilisateurs non amis
                return fetch('../php/api/get_users.php')
                    .then(res2 => res2.json())
                    .then(userRows => {
                        userList.innerHTML = '';

                        // 3) Afficher d’abord toutes les suggestions
                        Object.keys(recoMap).forEach(idStr => {
                            const uid    = parseInt(idStr, 10);
                            const person = recoMap[uid];
                            const li = document.createElement('li');
                            li.className = 'candidate-card suggestion-card';
                            li.innerHTML = `
                                <div class="candidate-info">
                                  <span class="candidate-name">
                                    ${person.prenom} ${person.nom}
                                  </span>
                                  <span class="candidate-meta">
                                    (${person.mutual} ami(s), ${person.interest} intérêt(s))
                                  </span>
                                </div>
                                <button class="btn-add" data-id="${uid}">Ajouter</button>
                            `;
                            const btn = li.querySelector('button.btn-add');
                            btn.addEventListener('click', () => addFriend(uid, btn));
                            userList.appendChild(li);
                        });

                        // 4) Puis, lister tous les autres “userRows” qui ne sont pas dans recoMap
                        userRows.forEach(u => {
                            if (recoMap[u.id]) return; // déjà affiché en suggestion

                            const li = document.createElement('li');
                            li.className = 'candidate-card normal-card';
                            li.innerHTML = `
                                <div class="candidate-info">
                                  <span class="candidate-name">${u.username}</span>
                                </div>
                                <button class="btn-add" data-id="${u.id}">Ajouter</button>
                            `;
                            const btn = li.querySelector('button.btn-add');
                            btn.addEventListener('click', () => addFriend(u.id, btn));
                            userList.appendChild(li);
                        });
                    });
            })
            .catch(err => {
                console.error('Erreur buildFriendCandidatesList :', err);
                userList.innerHTML = '<li>Impossible de charger la liste d’amis potentiels.</li>';
            });
    }

    // ─── 8) Initialisation au chargement de la page ────────────────────
    loadConversations();
    buildFriendCandidatesList();
    loadFeed();

    // → Rafraîchissement automatique du chat (public ou privé)
    setInterval(() => {
        if (currentConv) loadMessages();
        else            loadFeed();
    }, 2000);

    // → Rafraîchir la liste d’amis potentiels toutes les 30 secondes
    setInterval(() => {
        buildFriendCandidatesList();
    }, 30000);
});