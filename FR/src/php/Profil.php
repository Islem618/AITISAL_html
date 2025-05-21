<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <title>Akatsuki - Nutritium</title>
      <link rel="stylesheet" href="../css/normalize.css">
      <link rel="stylesheet" href="../css/Profil.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
      <link rel="icon" type="image/x-icon" href="../../images/logonutritium%20-%20Copie.ico" />
  </head>

  <body>
  <header>
  <nav>
      <ul class="menu">
          <li><a href="index.php">Home</a></li>
          <li><a href="chat.php">Let's Chat !</a></li>
          <li><a href="espaceuser.php">Profil</a></li>
      </ul>
  </nav>
</header>

<!--logo Nutritium-->

    <div class="background"></div>
    <div class="logo-container">
        <!--le logo en haut à gauche-->
        <a href="index.php">
            <img src="../../images/logonutritium.png" id="Logo1" alt="Logo EchoKey" title="Logo EchoKey">
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
                    Coordonnées
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
                   Paramètres
                  </button>
                </label>
              </li>

              <li></li>
            </ul>
            <a href="logout.php"></a>
            <button class="Déconnexion" onclick="window.location.href='logout.php'">  
              <img
                class="menu-icon"
                src="../../images/se-deconnecter.png"
                alt="Exit Icon"
              />
              Déconnexion
            </button>
          </div>
          <div class="profile-info">
            <h1>Édition du Profil</h1>

            <?php

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "siteweb";
            
            $conn = new mysqli($servername, $username, $password, $dbname);
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
          
            if (!isset($_SESSION['user_id'])) {
              // Redirigez vers la page de connexion ou gérez le cas où l'utilisateur n'est pas connecté
              header("Location: login.php");
              exit();
          }
          
          // Récupérez l'identifiant de l'utilisateur à partir de la session
          $user_id = $_SESSION['user_id'];
          
          
        
        
      
      

            $sql = "SELECT * FROM user WHERE id_User = '$user_id'";
            $result = $conn->query($sql);
            
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $first_name = $row["prenom"];
                $last_name = $row["nom"];
                $email = $row["email"];
                $phone = $row["telephone"];
                $adresse = $row["adresse"];
                $city = $row["ville"];
            } else {
                echo "No user found.";
            }
            
            $conn->close();
            ?>
            
            <!-- Section de coordonnées (formulaire) -->
            <div id="coordonnees" class="section profile" style="display: none">
              <div class="form-group mb-3">
                <label class="col-md-4 control-label">Prénom</label>
                <div class="col-md-8 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input name="first_name" id="first_name" placeholder="Prénom" class="form-control" type="text" value="<?php echo $first_name; ?>" disabled />
                  </div>
                </div>
              </div>
            
              <!-- Text input-->
            
              <div class="form-group mb-3">
                <label class="col-md-4 control-label">Nom</label>
                <div class="col-md-8 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input name="last_name" id="last_name" placeholder="Nom" class="form-control" type="text" value="<?php echo $last_name; ?>" disabled />
                  </div>
                </div>
              </div>
            
              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 control-label">E-Mail</label>
                <div class="col-md-8 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    <input name="email" id="email" placeholder="Adresse E-Mail" class="form-control" type="text" value="<?php echo $email; ?>" disabled />
                  </div>
                </div>
              </div>
            
              <!-- Text input-->
            
              <div class="form-group">
                <label class="col-md-4 control-label">Téléphone</label>
                <div class="col-md-8 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                    <input name="phone" id="phone" placeholder="Téléphone" class="form-control" type="text" value="<?php echo $phone; ?>" disabled />
                  </div>
                </div>
              </div>
            
              <!-- Text input-->
            
              <div class="form-group mb-2">
                <label class="col-md-4 control-label">Adresse</label>
                <div class="col-md-8 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                    <input name="adresse" id="adresse" placeholder="Adresse" class="form-control" type="text" value="<?php echo $adresse; ?>" disabled />
                  </div>
                </div>
              </div>
            
              <!-- Text input-->
            
              <div class="form-group">
                <label class="col-md-4 control-label">Ville</label>
                <div class="col-md-8 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                    <input name="city" id="city" placeholder="Ville" class="form-control" type="text" value="<?php echo $city; ?>" disabled />
                  </div>
                </div>
              </div>
            </div>
            

            <!-- Section des paramètres (vide dans le code fourni) -->
            <div id="parametres" class="section profile" style="display: none">
              
              <h5 div="mdp">Modifier votre Mot de Passe en toute sécurité</h5>
                <div class="form-group mb-5">
                  <div class="col-md-12 inputGroupContainer">
                    <label class="control-label">Mot de passe actuel</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                      <input id="current_password" name="current_password" placeholder="Mot de passe actuel" class="form-control" type="password"  />
                    </div>
                  </div>
                </div>
                <div class="form-group mb-5">
                  <div class="col-md-12 inputGroupContainer">
                    <label class="control-label">Nouveau mot de passe</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                      <input id="new_password" name="new_password" placeholder="Nouveau mot de passe" class="form-control" type="password"  />
                    </div>
                  </div>
                </div>
                <div class="form-group mb-0">
                  <div class="col-md-12 inputGroupContainer">
                    <label class="control-label">Confirmer le nouveau mot de passe</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                      <input id="confirm_password" name="confirm_password" placeholder="Confirmer le nouveau mot de passe" class="form-control" type="password"  />
                      <span id="passwordError" style="color: red;"></span>
                    </div>
                </div>
              </div>
            

            </div>
            <button class="Edition" type="button" id="Modifier" onclick="activerEdition()">
              Modifier
            </button>
            <button
              class="Edition"
              type="button"
              id="Enregistrer"
              onclick="enregistrerEdition()"
              style="display: none"
            >
              Enregistrer
            </button>
            <button class="Edition" type="button" id="Maj" style="display: none" onclick="majPassword()">
            Mettre à jour
          </button>
            <button class="Edition" type="button" id="AnnulerEdition" style="display: none" onclick="annulerEdition()">
              Annuler
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <footer>
      <div class="footer">
      <img src="../../images/footernutritium.png" id="LogosFooter" alt="LogosFooter" title="LogosFooter"> <!--logo Nutritium-->
  <li>
    <!--logo déconnexion-->
  </li>
        <nav>
          <ul>
            <li><a href="CGU.php" id="ga" target="_blank">C.G.U</a></li>
            <li>
              <a href="https://www.isep.fr/" id="ga" target="_blank"
                >Nos investisseurs</a
              >
            </li>
            <li><a href="faq.php" id="ga" target="_blank">Contact</a></li>
          </ul>
        </nav>
      </div>
    </footer>
    <script src="../js/Profil.js"></script>

  </body>
</html>