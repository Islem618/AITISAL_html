<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="../css/Connexion.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>
  <body background="../../images/background.png">

    
    <!--Formulaire de connexion-->
    <div class="wrapper" id="loginWrapper">
      <form id="loginForm" action="login.php" method="post">
        <h1>Log In</h1>
        <div class="input-box">
          <input type="text" id="loginEmail" name="loginEmail" placeholder="Email" required /><i
            class="bx bxs-user"
          ></i>
        </div>
        <div class="input-box">
          <input
            type="password"
            id="loginPassword"
            name="loginPassword"
            placeholder="Password"
            required
          /><i class="bx bxs-lock-alt"></i>
        </div>

        <div class="remember-forgot">
          <label><input type="checkbox" /> Remember Me</label>
          <a href="#">Forgot Password?</a>
        </div>

        <button type="submit" class="btn">Log In</button>
        <p>Don't have an account?</p>
        <button type="button" id="registerBtn" class="btn">Register</button>
      </form>
    </div>


 <!-- Formulaire d'enregistrement -->
 <div class="wrapper2" id="registerWrapper" style="display: none">
      <form id="registerForm" action="register.php" method="post">
        <h1>Registration</h1>
        <div class="name-fields">
          <div class="input-box">
            <!-- Champ Prénom -->
            <input type="text" id="registerFirstName" name="registerFirstName" placeholder="First Name" required />
            <i class="bx bxs-user"></i>
          </div>
          <div class="input-box">
            <!-- Champ Nom -->
            <input type="text" id="registerLastName" name="registerLastName" placeholder="Last Name" required />
            <i class="bx bxs-user"></i>
          </div>
        </div>

        <div class="input-box">
          <input type="text" id="registerEmail" name="registerEmail" placeholder="Email" required />
          <i class="bx bxs-user"></i>
        </div>
        <div class="input-box">
          <input type="password" id="registerPassword" name="registerPassword" placeholder="Password" required />
          <i class="bx bxs-lock-alt"></i>
        </div>
        <div class="input-box">
          <input type="password" id="RepeatPassword" name="RepeatPassword" placeholder="Repeat Password" required />
          <i class="bx bxs-lock-alt"></i>
        </div>

        <button type="submit" class="btn">Register</button>
        <p>Already have an account?</p>
        <button type="button" id="loginBtn" class="btn">Log In</button>
      </form>
    </div>
    <script src="../js/connexion.js"></script>
  </body>
</html>