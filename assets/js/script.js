// Gestion de l'affichage des formulaires de connexion et d'inscription

document.addEventListener("DOMContentLoaded", function () {
    const signinForm = document.getElementById("signin-form");
    const signupForm = document.getElementById("signup-form");
    const connBtn = document.getElementById("conn");
    const registerBtn = document.getElementById("register");

    // Ouvrir le formulaire de connexion
    connBtn.addEventListener("click", function (event) {
        event.preventDefault();
        signinForm.style.display = "block";
        signupForm.style.display = "none";
    });

    registerBtn.addEventListener("click", function (event) {
        event.preventDefault();
        signupForm.style.display = "block";
        signinForm.style.display = "none";
    });

    // Fermer un formulaire en cliquant en dehors de celui-ci
    window.addEventListener("click", function (event) {
        if (event.target === signinForm) signinForm.style.display = "none";
        if (event.target === signupForm) signupForm.style.display = "none";
    });
});



