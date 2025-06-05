function updateModifierButtonState(sectionId) {
  const modifierButton = document.getElementById("Modifier");
  const majButton = document.getElementById("Maj");
  const annulerButton = document.getElementById("AnnulerEdition");

  modifierButton.style.display = sectionId === "coordonnees" ? "inline" : "none";
  majButton.style.display = sectionId === "parametres" ? "inline" : "none";

  document.getElementById("Enregistrer").style.display = "none";
  annulerButton.style.display = "none";
}

function activerEdition() {
  const champs = document.querySelectorAll(
      "#coordonnees input[disabled]:not(#first_name):not(#last_name):not(#email)"
  );
  champs.forEach((champ) => champ.removeAttribute("disabled"));

  document.getElementById("Enregistrer").style.display = "inline";
  document.getElementById("AnnulerEdition").style.display = "inline";
  document.getElementById("Modifier").style.display = "none";
}

function desactiverChampsCoordonnees() {
  const champs = document.querySelectorAll("#coordonnees input:not([disabled])");
  champs.forEach((champ) => champ.setAttribute("disabled", true));

  document.getElementById("Modifier").style.display = "inline";
  document.getElementById("Enregistrer").style.display = "none";
  document.getElementById("AnnulerEdition").style.display = "none";
}

function showSection(sectionId) {
  document.querySelectorAll(".section.profile").forEach((section) => {
    section.style.display = section.id === sectionId ? "block" : "none";
  });

  updateModifierButtonState(sectionId);
}

function enregistrerEdition() {
  const phone = document.getElementById("phone").value;
  const adresse = document.getElementById("adresse").value;
  const city = document.getElementById("city").value;

  desactiverChampsCoordonnees();

  $.ajax({
    url: "update_profile.php",
    type: "POST",
    data: { phone, adresse, city },
    dataType: "json",
    success: function (res) {
      alert(res.status === "success" ? "Profil mis à jour !" : "Échec de la mise à jour.");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    },
  });
}

function annulerEdition() {
  desactiverChampsCoordonnees();
}

function majPassword() {
  const oldPassword = document.getElementById("current_password").value;
  const newPassword = document.getElementById("new_password").value;
  const confirmPassword = document.getElementById("confirm_password").value;

  if (newPassword !== confirmPassword) {
    alert("Les nouveaux mots de passe ne correspondent pas !");
    return;
  }

  $.ajax({
    url: "update_password.php",
    type: "POST",
    data: { oldPassword, newPassword },
    dataType: "json",
    success: function (res) {
      alert(res.status === "success"
          ? "Mot de passe mis à jour avec succès !"
          : "Erreur : " + res.message);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR.responseText);
      console.error("Erreur : " + textStatus + ", " + errorThrown);
    },
  });
}

function initFooterLogoHeight() {
  const footerHeight = document.querySelector(".footer").offsetHeight;
  document.getElementById("LogosFooter").style.maxHeight = footerHeight + "px";
}

// Envoi de la nouvelle photo de profil
function envoyerPhotoProfil() {
  const input = document.getElementById("profile-picture-input");
  const fichier = input.files[0];
  if (!fichier) return;

  const formData = new FormData();
  formData.append("profile_picture", fichier);

  fetch("upload_profile_picture.php", {
    method: "POST",
    body: formData,
  })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          alert("Photo mise à jour !");
          location.reload();
        } else {
          alert("Erreur : " + data.message);
        }
      })
      .catch((err) => {
        console.error("Erreur lors de l'envoi :", err);
      });
}

// Initialisation
document.addEventListener("DOMContentLoaded", function () {
  initFooterLogoHeight();

  // Action sur le bouton d'envoi de photo
  const btnPhoto = document.getElementById("profile-picture-submit");
  if (btnPhoto) {
    btnPhoto.addEventListener("click", envoyerPhotoProfil);
  }

  // Gestion d’onglets profil
  showSection("coordonnees");
});
