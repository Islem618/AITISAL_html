//footer
// Popup servant à la déconnexion
function deconnexion() {
    var result = confirm("Do you really want to log out?");
    if (result == true) {
        alert("Thank you for your visit");

        // Effectuer une redirection vers le script PHP de déconnexion
        window.location.href = 'logout.php';
    } else {
        // On ferme juste la popup puisqu'on ne se déconnecte pas en cliquant sur annuler
    }
}


// JS Hover bouton déconnexion
function changerImage(etat) {
    var img = document.getElementById("imgdeco");
    if (etat === "survol") {
        img.src = "../../images/hover-signoutaitisal.png"; // Chemin vers l'image au survol
    } else {
        img.src = "../../images/signoutaitisal.png"; // Chemin vers l'image normale
    }
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

document.addEventListener('DOMContentLoaded', () => {
    const listEl = document.getElementById('interests-list');
    const form   = document.getElementById('interests-form');
    const msg    = document.getElementById('interests-msg');

    let selectedIds = [];

    // 1) Récupérer les IDs d'intérêts déjà sélectionnés pour cet utilisateur
    fetch('api/get_user_interests.php')
        .then(res => res.json())
        .then(ids => {
            if (Array.isArray(ids)) {
                selectedIds = ids.map(i => parseInt(i, 10));
            } else {
                selectedIds = [];
            }
            // 2) Charger la liste complète des intérêts
            return fetch('api/get_interests.php');
        })
        .then(res2 => res2.json())
        .then(rows => {
            if (!Array.isArray(rows)) {
                listEl.innerHTML = '<li>Erreur de chargement</li>';
                return;
            }
            listEl.innerHTML = '';
            rows.forEach(r => {
                const li = document.createElement('li');
                const cb = document.createElement('input');
                cb.type  = 'checkbox';
                cb.id    = 'int_' + r.id;
                cb.value = r.id;
                // cocher si selected === 1 OU si l’ID figure dans selectedIds
                if (r.selected == 1 || selectedIds.includes(r.id)) {
                    cb.checked = true;
                }

                const label = document.createElement('label');
                label.htmlFor     = cb.id;
                label.textContent = r.label;

                li.appendChild(cb);
                li.appendChild(label);
                listEl.appendChild(li);
            });
        })
        .catch(err => {
            console.error('Erreur load_interests :', err);
            listEl.innerHTML = '<li>Impossible de charger</li>';
        });

    // 3) À la soumission du formulaire : on ré‐récupère toutes les cases cochées…
    form.addEventListener('submit', e => {
        e.preventDefault();
        msg.textContent = '';
        const checkedIds = [];
        document.querySelectorAll('#interests-list input[type=checkbox]')
            .forEach(cb => {
                if (cb.checked) checkedIds.push(parseInt(cb.value, 10));
            });

        fetch('api/save_interests.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ interests: checkedIds })
        })
            .then(res => res.json())
            .then(resData => {
                if (resData.status === 'success') {
                    msg.style.color = 'green';
                    msg.textContent = 'Vos intérêts ont bien été enregistrés.';
                } else {
                    msg.style.color = 'red';
                    msg.textContent = 'Erreur : ' + (resData.message || '');
                }
            })
            .catch(err => {
                console.error('Erreur save_interests :', err);
                msg.style.color = 'red';
                msg.textContent = 'Erreur réseau…';
            });
    });
});
