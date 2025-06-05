<?php
// Démarrez la session au début de chaque page PHP
session_start();

// Assurez-vous que l'utilisateur est connecté avant de procéder
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: Connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../css/normalize.css" />
    <link rel="stylesheet" href="../css/interests.css" />
    <script src="../js/jquery.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../../images/logonutritium%20-%20Copie.ico" />
    <title>AITISAL</title>
</head>
<body>
<header>
    <nav>
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="chat.php">Let's Chat !</a></li>
            <li><a href="espaceuser.php">Profile</a></li>
        </ul>
    </nav>
</header>
<!-- Pastille notification (en haut à droite) -->
<span id="notification-badge">
  0
</span>

<!-- Panneau des notifications (au-dessus du contenu, masqué par défaut) -->
<section id="notification-box">
    <h3>Notifications</h3>
    <ul id="notification-panel"></ul>
</section>
<main>
    <h1>My interests</h1>
    <form id="interests-form">
        <ul id="interests-list">
            <!-- sera peuplé dynamiquement par JS -->
        </ul>
        <button type="submit">Save my interests</button>
    </form>
    <div id="interests-msg" style="margin-top:1rem;color:green;"></div>
</main>

<script>
    /* ─── Les fonctions de déconnexion / hover (inchangées) ───────────────── */

    function deconnexion() {
        var result = confirm("Do you really want to log out?");
        if (result) {
            alert("Thank you for your visit");
            window.location.href = 'logout.php';
        }
    }

    function changerImage(etat) {
        var img = document.getElementById("imgdeco");
        if (!img) return;
        img.src = (etat === "survol")
            ? "../../images/déconnexion2-hover.png"
            : "../../images/déconnexion2.png";
    }

    /* ─── Script principal pour charger / afficher / enregistrer les intérêts ─ */

    document.addEventListener('DOMContentLoaded', () => {
        const listEl = document.getElementById('interests-list');
        const form   = document.getElementById('interests-form');
        const msg    = document.getElementById('interests-msg');

        let selectedIds = []; // contiendra les IDs déjà cochés

        // 1) On récupère d'abord les IDs déjà sélectionnés par l’utilisateur
        fetch('api/get_user_interests.php')
            .then(res => res.json())
            .then(ids => {
                if (Array.isArray(ids)) {
                    selectedIds = ids.map(i => parseInt(i,10));
                } else {
                    selectedIds = [];
                }
                // 2) Ensuite, on charge toutes les options d’intérêts
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
                    // si l’ID est dans selectedIds, on coche
                    if (selectedIds.includes(r.id)) {
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

        // 3) À la soumission du formulaire, on collecte les checkbox cochées puis on envoie en POST
        form.addEventListener('submit', e => {
            e.preventDefault();
            msg.textContent = '';
            const checkedIds = [];
            document.querySelectorAll('#interests-list input[type=checkbox]')
                .forEach(cb => {
                    if (cb.checked) checkedIds.push(parseInt(cb.value,10));
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
                        msg.textContent = 'Your interests have been recorded.';
                    } else {
                        msg.style.color = 'red';
                        msg.textContent = 'Erreur : ' + (resData.message||'');
                    }
                })
                .catch(err => {
                    console.error('Erreur save_interests :', err);
                    msg.style.color = 'red';
                    msg.textContent = 'Erreur réseau…';
                });
        });
    });
</script>

<img src="../../images/footernutritium.png" id="LogosFooter" alt="LogosFooter" title="LogosFooter">
<img src="../../images/déconnexion2.png" id="imgdeco" alt="logo déconnexion" title="logo déconnexion"
     onmouseover="changerImage('survol')" onmouseout="changerImage('normal')" onclick="deconnexion()">

<footer>
    <div class="footer">
        <nav>
            <ul>
                <li><a href="CGU.php" id="ga" target="_blank">G.C.U</a></li>
                <li><a href="https://www.isep.fr/" id="ga" target="_blank">Our investors</a></li>
                <li><a href="faq.php" id="ga" target="_blank">Contact</a></li>
            </ul>
        </nav>
    </div>
</footer>

<script src="../js/interests.js"></script>
<script src="../js/notifications.js"></script>
</body>
</html>
