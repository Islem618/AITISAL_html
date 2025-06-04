// notifications.js

function deconnexion() {
    if (confirm("Voulez-vous vraiment vous déconnecter ?")) {
        alert("Merci de votre visite");
        window.location.href = 'logout.php';
    }
}

function changerImage(etat) {
    const img = document.getElementById("imgdeco");
    if (!img) return;
    img.src = (etat === "survol")
        ? "../../images/déconnexion2-hover.png"
        : "../../images/déconnexion2.png";
}

document.addEventListener('DOMContentLoaded', () => {
    const badge = document.getElementById('notification-badge');
    const box   = document.getElementById('notification-box');
    const panel = document.getElementById('notification-panel');

    let isBoxVisible = false;

    // Clic sur la pastille pour ouvrir/fermer le panneau
    badge.addEventListener('click', () => {
        isBoxVisible = !isBoxVisible;
        box.style.display = isBoxVisible ? 'block' : 'none';
        if (isBoxVisible) {
            markNotificationsRead();
        }
    });

    // Clic hors du panneau = fermeture
    document.addEventListener('click', (e) => {
        if (isBoxVisible && !box.contains(e.target) && e.target !== badge) {
            isBoxVisible = false;
            box.style.display = 'none';
        }
    });

    // Récupération + affichage des notifications
    async function fetchNotifications() {
        try {
            const resp = await fetch('../php/api/get_notifications.php', {
                cache: 'no-store'
            });
            if (!resp.ok) {
                console.error('fetchNotifications : statut non OK', resp.status);
                return;
            }

            const data = await resp.json();
            if (!Array.isArray(data)) {
                console.error('fetchNotifications : réponse non JSON', data);
                return;
            }

            // Mise à jour du badge
            if (data.length === 0) {
                badge.style.display = 'none';
                badge.textContent = '0';
            } else {
                badge.style.display = 'inline-block';
                badge.textContent = data.length;
            }

            // Affichage du détail dans le panneau
            panel.innerHTML = '';
            if (data.length === 0) {
                const li = document.createElement('li');
                li.textContent = 'Aucune nouvelle notification.';
                panel.appendChild(li);
            } else {
                data.forEach(note => {
                    const li = document.createElement('li');
                    li.textContent = note.text || 'Nouvelle notification';
                    panel.appendChild(li);
                });
            }

        } catch (err) {
            console.error('fetchNotifications :', err);
        }
    }

    // Marque toutes les notifications comme lues
    async function markNotificationsRead() {
        try {
            await fetch('../php/api/mark_notifications_read.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({})
            });
            badge.style.display = 'none';
            badge.textContent = '0';
        } catch (err) {
            console.error('markNotificationsRead :', err);
        }
    }

    // Appel initial
    fetchNotifications();

    // Rafraîchissement automatique toutes les 5s
    setInterval(fetchNotifications, 5000);
});