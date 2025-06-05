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

// ─── Code principal pour le chat ─────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const feed          = document.getElementById('chat-feed');
    const form          = document.getElementById('chat-form');
    const input         = document.getElementById('chat-input');
    const userList      = document.getElementById('friend-candidates-list');
    const convList      = document.getElementById('conversation-list');
    const me            = window.currentUserId || 0;
    let   currentConv   = null;

    // Si on n’est pas sur chat.php, on s’arrête
    if (!feed || !form || !input || !userList || !convList) return;

    // ─── Bouton “← Chat public” ─────────────────────────────────────────
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
    feed.parentNode.insertBefore(returnBtn, feed);

    // ─── 1) Charger les conversations privées ───────────────────────
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

                // Sélection automatique de la première conversation si aucune sélection actuelle
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

    // ─── 2) Charger les messages privés (avec avatars) ───────────────
    function loadMessages() {
        if (!currentConv) return;
        fetch(`../php/api/get_messages.php?conversation_id=${currentConv}`)
            .then(resp => resp.json())
            .then(msgs => {
                feed.innerHTML = '';
                msgs.forEach(m => {
                    // Création d’une bulle de message
                    const div = document.createElement('div');
                    div.className = 'message ' + (m.from_id === me ? 'me' : 'them');

                    // On récupère l’URL de l’avatar. Comme on est dans chat.js (FR/src/js),
                    // on remonte de deux niveaux pour atteindre la racine FR/, puis on ajoute photo_path.
                    const avatarUrl = `../../${m.photo_path}`;

                    // Injection du HTML avec l’avatar, le nom, l’horodatage et le contenu
                    div.innerHTML = `
                      <div class="message-header">
                        <img class="avatar" src="${avatarUrl}" alt="Photo de ${m.prenom}" />
                        <strong>${m.prenom} ${m.nom}</strong>
                        <small class="timestamp">${m.created_at}</small>
                      </div>
                      <div class="message-content">${m.content}</div>
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

    // ─── 3) Charger le chat public (avec avatars) ────────────────────
    function loadFeed() {
        fetch('../php/api/get_feed.php')
            .then(resp => resp.json())
            .then(posts => {
                feed.innerHTML = '';
                posts.forEach(p => {
                    const div = document.createElement('div');
                    div.className = 'post';

                    // On génère l’URL absolu de l’avatar (remonter de 2 niveaux puis photo_path)
                    const avatarUrlPub = `../../${p.photo_path}`;

                    div.innerHTML = `
                      <div class="message-header">
                        <img class="avatar" src="${avatarUrlPub}" alt="Photo de ${p.prenom}" />
                        <strong>${p.prenom} ${p.nom}</strong>
                        <small class="timestamp">${p.created_at}</small>
                      </div>
                      <div class="message-content">${p.content}</div>
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

    // ─── 4) Envoi d’un message privé ou public ───────────────────────
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

    // ─── 5) Ajouter un ami + créer conversation ──────────────────────
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
                    buildFriendCandidatesList();
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

    // ─── 6) Ouvrir/créer conversation sur clic d’un nom ───────────────
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
                        .then(resp2 => {
                            if (!resp2.ok) {
                                return resp2.json().then(errObj => {
                                    throw new Error(errObj.message || 'Erreur interne');
                                });
                            }
                            return resp2.json();
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

    // ─── 7) Construire / afficher “Amis potentiels” ────────────────────
    function buildFriendCandidatesList() {
        fetch('../php/api/get_recommendations.php')
            .then(res => res.text())
            .then(txt => {
                try {
                    return JSON.parse(txt);
                } catch (e) {
                    console.error('get_recommendations: JSON invalide →', txt);
                    throw e;
                }
            })
            .then(recoRows => {
                return fetch('../php/api/get_users.php')
                    .then(res2 => res2.text())
                    .then(txt2 => {
                        try {
                            return {
                                recoRows: recoRows,
                                userRows: JSON.parse(txt2)
                            };
                        } catch (e2) {
                            console.error('get_users: JSON invalide →', txt2);
                            throw e2;
                        }
                    });
            })
            .then(({ recoRows, userRows }) => {
                const recoSet = new Set();
                if (Array.isArray(recoRows)) {
                    recoRows.forEach(r => recoSet.add(r.suggestion_id));
                } else {
                    recoRows = [];
                }

                userList.innerHTML = '';

                if (recoRows.length === 0) {
                    const noRecLi = document.createElement('li');
                    noRecLi.textContent = 'Aucune suggestion pour l’instant.';
                    noRecLi.className = 'candidate-card normal-card';
                    userList.appendChild(noRecLi);
                } else {
                    recoRows.forEach(r => {
                        const uid = r.suggestion_id;
                        const li = document.createElement('li');
                        li.className = 'candidate-card suggestion-card';
                        li.innerHTML = `
              <div class="candidate-info">
                <span class="candidate-name">
                  ${r.suggestion_prenom} ${r.suggestion_nom}
                </span>
                <span class="candidate-meta">
                  (${r.mutual_count} ami(s), ${r.interest_count} intérêt(s))
                </span>
              </div>
              <button class="btn-add" data-id="${uid}">Ajouter</button>
            `;
                        const btn = li.querySelector('button.btn-add');
                        btn.addEventListener('click', () => addFriend(uid, btn));
                        userList.appendChild(li);
                    });
                }

                if (!Array.isArray(userRows)) userRows = [];

                userRows.forEach(u => {
                    if (recoSet.has(u.id)) {
                        return;
                    }
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
            })
            .catch(err => {
                console.error('Erreur buildFriendCandidatesList :', err);
                userList.innerHTML = '<li>Impossible de charger la liste d’amis potentiels.</li>';
            });
    }

    // ─── 8) Chargement initial + intervals ──────────────────────────
    loadConversations();
    buildFriendCandidatesList();
    loadFeed();

    setInterval(() => {
        if (currentConv) loadMessages();
        else            loadFeed();
    }, 2000);

    setInterval(() => {
        buildFriendCandidatesList();
    }, 30000);
});