<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>AITISAL</title>
    <link rel="stylesheet" href="../css/normalize.css" />
    <link rel="stylesheet" href="../css/Profil.css" />
    <link
            rel="stylesheet"
            href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link
            rel="icon"
            type="image/x-icon"
            href="../../images/logoAITISAL.ico"
    />
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
<span id="notification-badge">0</span>

<!-- Panneau des notifications (au-dessus du contenu, masqué par défaut) -->
<section id="notification-box">
    <h3>Notifications</h3>
    <ul id="notification-panel"></ul>
</section>

<div class="background"></div>
<div class="logo-container">
    <!-- le logo en haut à gauche -->
    <a href="index.php">
        <img
                src="../../images/logoaitisal.png"
                id="Logo1"
                alt="Logo EchoKey"
                title="Logo EchoKey"
        />
    </a>
</div>

<div class="ii">
    <div class="container1">
        <div class="content">
            <div class="sidebar">
                <ul class="menu2">
                    <li>
                        <label for="Coordonnées" class="label_menu">
                            <img
                                    class="menu-icon"
                                    src="../../images/profil.png"
                                    alt="Users Icon"
                            />
                            <button onclick="showSection('coordonnees')">
                                Contact details
                            </button>
                        </label>
                    </li>
                    <li>
                        <label for="Paramètres" class="label_menu">
                            <img
                                    class="menu-icon"
                                    src="../../images/parametres-des-engrenages.png"
                                    alt="Paramètres des engrenages"
                            />
                            <button onclick="showSection('parametres')">
                                Settings
                            </button>
                        </label>
                    </li>
                    <li>
                        <label for="PhotoProfil" class="label_menu">
                            <img
                                    class="menu-icon"
                                    src="../../images/pdp.png"
                                    alt="Icône Photo"
                            />
                            <button onclick="showSection('photo_profil')">
                                Profile picture
                            </button>
                        </label>
                    </li>
                </ul>
                <button
                        class="Déconnexion"
                        onclick="window.location.href='logout.php'"
                >
                    <img
                            class="menu-icon"
                            src="../../images/se-deconnecter.png"
                            alt="Exit Icon"
                    />
                    Sign out
                </button>
            </div>

            <div class="profile-info">
                <h1>Profile Edition</h1>

                <?php
                // Connexion à la base
                $servername = "localhost";
                $username   = "root";
                $password   = "";
                $dbname     = "siteweb";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if (!isset($_SESSION['user_id'])) {
                    header("Location: login.php");
                    exit();
                }

                $user_id = $_SESSION['user_id'];

                // Récupérer les infos de l’utilisateur, y compris photo_path
                $sql  = "SELECT prenom, nom, email, telephone, adresse, ville, photo_path 
                     FROM user 
                     WHERE id_User = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row        = $result->fetch_assoc();
                    $first_name = $row["prenom"];
                    $last_name  = $row["nom"];
                    $email      = $row["email"];
                    $phone      = $row["telephone"];
                    $adresse    = $row["adresse"];
                    $city       = $row["ville"];
                    $photoPath  = $row["photo_path"]; // chemin relatif, p. ex. "uploads/photos/profil_32.jpg"
                } else {
                    echo "Aucun utilisateur trouvé.";
                    $photoPath = "";
                }

                $stmt->close();
                $conn->close();
                ?>

                <!-- ===================== -->
                <!-- Onglet “Coordonnées” -->
                <div
                        id="coordonnees"
                        class="section profile"
                        style="display: none"
                >
                    <div class="form-group mb-3">
                        <label class="col-md-4 control-label">First name</label>
                        <div class="col-md-8 inputGroupContainer">
                            <div class="input-group">
                    <span class="input-group-addon"
                    ><i class="glyphicon glyphicon-user"></i
                        ></span>
                                <input
                                        name="first_name"
                                        id="first_name"
                                        placeholder="Prénom"
                                        class="form-control"
                                        type="text"
                                        value="<?php echo htmlspecialchars($first_name); ?>"
                                        disabled
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="col-md-4 control-label">Name</label>
                        <div class="col-md-8 inputGroupContainer">
                            <div class="input-group">
                    <span class="input-group-addon"
                    ><i class="glyphicon glyphicon-user"></i
                        ></span>
                                <input
                                        name="last_name"
                                        id="last_name"
                                        placeholder="Nom"
                                        class="form-control"
                                        type="text"
                                        value="<?php echo htmlspecialchars($last_name); ?>"
                                        disabled
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">E-Mail</label>
                        <div class="col-md-8 inputGroupContainer">
                            <div class="input-group">
                    <span class="input-group-addon"
                    ><i class="glyphicon glyphicon-envelope"></i
                        ></span>
                                <input
                                        name="email"
                                        id="email"
                                        placeholder="Adresse E-Mail"
                                        class="form-control"
                                        type="text"
                                        value="<?php echo htmlspecialchars($email); ?>"
                                        disabled
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Phone</label>
                        <div class="col-md-8 inputGroupContainer">
                            <div class="input-group">
                    <span class="input-group-addon"
                    ><i class="glyphicon glyphicon-earphone"></i
                        ></span>
                                <input
                                        name="phone"
                                        id="phone"
                                        placeholder="Téléphone"
                                        class="form-control"
                                        type="text"
                                        value="<?php echo htmlspecialchars($phone); ?>"
                                        disabled
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="col-md-4 control-label">Adress</label>
                        <div class="col-md-8 inputGroupContainer">
                            <div class="input-group">
                    <span class="input-group-addon"
                    ><i class="glyphicon glyphicon-home"></i
                        ></span>
                                <input
                                        name="adresse"
                                        id="adresse"
                                        placeholder="Adresse"
                                        class="form-control"
                                        type="text"
                                        value="<?php echo htmlspecialchars($adresse); ?>"
                                        disabled
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">City</label>
                        <div class="col-md-8 inputGroupContainer">
                            <div class="input-group">
                    <span class="input-group-addon"
                    ><i class="glyphicon glyphicon-home"></i
                        ></span>
                                <input
                                        name="city"
                                        id="city"
                                        placeholder="Ville"
                                        class="form-control"
                                        type="text"
                                        value="<?php echo htmlspecialchars($city); ?>"
                                        disabled
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== -->
                <!-- Onglet “Paramètres” -->
                <div
                        id="parametres"
                        class="section profile"
                        style="display: none"
                >
                    <h5>Change your password safely</h5>
                    <div class="form-group mb-5">
                        <div class="col-md-12 inputGroupContainer">
                            <label class="control-label">Current password</label>
                            <div class="input-group">
                    <span class="input-group-addon"
                    ><i class="glyphicon glyphicon-user"></i
                        ></span>
                                <input
                                        id="current_password"
                                        name="current_password"
                                        placeholder="Current password"
                                        class="form-control"
                                        type="password"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-5">
                        <div class="col-md-12 inputGroupContainer">
                            <label class="control-label">New password</label>
                            <div class="input-group">
                    <span class="input-group-addon"
                    ><i class="glyphicon glyphicon-user"></i
                        ></span>
                                <input
                                        id="new_password"
                                        name="new_password"
                                        placeholder="New password"
                                        class="form-control"
                                        type="password"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <div class="col-md-12 inputGroupContainer">
                            <label class="control-label"
                            >Confirm The New Password</label
                            >
                            <div class="input-group">
                    <span class="input-group-addon"
                    ><i class="glyphicon glyphicon-user"></i
                        ></span>
                                <input
                                        id="confirm_password"
                                        name="confirm_password"
                                        placeholder="Confirm The New Password"
                                        class="form-control"
                                        type="password"
                                />
                                <span id="passwordError" style="color: red;"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== -->
                <!-- Onglet “Photo de profil” -->
                <div
                        id="photo_profil"
                        class="section profile"
                        style="display: none; text-align: center;"
                >
                    <h3>Change your profile picture</h3>

                    <!-- Afficher la miniature actuelle (si déjà enregistrée) -->
                    <?php if (!empty($photoPath)) : ?>
                        <div style="margin: 15px auto;">
                            <img
                                    src="../../<?php echo htmlspecialchars($photoPath); ?>"
                                    alt="Photo actuelle"
                                    style="
                      max-width: 150px;
                      height: auto;
                      border-radius: 50%;
                      border: 1px solid #ccc;
                    "
                            />
                        </div>
                    <?php endif; ?>

                    <!-- Image de prévisualisation du fichier sélectionné -->
                    <div id="preview-container" style="margin-bottom: 15px;">
                        <img
                                id="previewPhoto"
                                src=""
                                alt="Prévisualisation"
                                style="display: none; max-width: 150px; border-radius: 50%; border: 1px solid #666;"
                        />
                    </div>

                    <!-- Formulaire d’upload -->
                    <form
                            action="../php/api/upload_photo.php"
                            method="POST"
                            enctype="multipart/form-data"
                            style="display: inline-block; text-align: left;"
                    >
                        <div class="form-group">
                            <label for="profile_photo"
                            >Pick a file :</label
                            >
                            <input
                                    type="file"
                                    name="profile_photo"
                                    id="profile_photo"
                                    accept=".jpg, .jpeg, .png, .gif"
                                    required
                            />
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Change the picture
                        </button>
                    </form>
                </div>

                <!-- Boutons “Modifier / Enregistrer / Mettre à jour / Annuler” -->
                <button
                        class="Edition"
                        type="button"
                        id="Modifier"
                        onclick="activerEdition()"
                        style="display: none"
                >
                    Change
                </button>
                <button
                        class="Edition"
                        type="button"
                        id="Enregistrer"
                        onclick="enregistrerEdition()"
                        style="display: none"
                >
                    Save
                </button>
                <button
                        class="Edition"
                        type="button"
                        id="Maj"
                        style="display: none"
                        onclick="majPassword()"
                >
                    Update
                </button>
                <button
                        class="Edition"
                        type="button"
                        id="AnnulerEdition"
                        style="display: none"
                        onclick="annulerEdition()"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="footer">
        <img
                src="../../images/footeraitisal.png"
                id="LogosFooter"
                alt="LogosFooter"
                title="LogosFooter"
        />
        <nav>
            <ul>
                <li><a href="CGU.php" id="ga" target="_blank">G.C.U</a></li>
                <li>
                    <a href="https://www.isep.fr/" id="ga" target="_blank"
                    >Our investors</a
                    >
                </li>
                <li><a href="faq.php" id="ga" target="_blank">Contact</a></li>
            </ul>
        </nav>
    </div>
</footer>

<!-- ================================== -->
<!-- Scripts JS additionnels pour ce seul fichier -->
<script src="../js/Profil.js"></script>
<script src="../js/notifications.js"></script>

<script>
    // Lorsqu’on change de fichier dans <input id="profile_photo">, afficher la prévisualisation
    document
        .getElementById("profile_photo")
        .addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (!file) {
                document.getElementById("previewPhoto").style.display = "none";
                return;
            }
            const allowedExts = ["jpg", "jpeg", "png", "gif"];
            const ext = file.name.split(".").pop().toLowerCase();
            if (!allowedExts.includes(ext)) {
                alert("Extension non autorisée. Choisissez JPG, PNG ou GIF.");
                this.value = "";
                document.getElementById("previewPhoto").style.display = "none";
                return;
            }
            // Créer un objet FileReader pour lire le fichier localement
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.getElementById("previewPhoto");
                img.src = e.target.result;
                img.style.display = "block";
            };
            reader.readAsDataURL(file);
        });
</script>
</body>
</html>
