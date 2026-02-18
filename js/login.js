$(document).delegate(".loading", "click", function () {
  var count = 0;
  loadingElement = $(this);
  if (isLoad) {
    intervalLoading = setInterval(function () {
      count += 1;
      if (count == 1) {
        loadingElement.html("PROCESSANDO.");
      }
      if (count == 2) {
        loadingElement.html("PROCESSANDO..");
      }
      if (count == 3) {
        loadingElement.html("PROCESSANDO...");
        count = 0;
      }
    }, 500);
  } else {
    if (intervalLoading) {
      clearInterval(intervalLoading);
    }
  }
});

document.addEventListener("DOMContentLoaded", function () {

    const loginForm = document.getElementById("loginForm");
    const forgotForm = document.getElementById("forgotForm");

    window.toggleForgot = function () {
        loginForm.classList.toggle("hidden");
        forgotForm.classList.toggle("hidden");
    };


    window.togglePassword = function () {
        const passwordInput = document.getElementById("pass");
        const icon = document.querySelector(".toggle-password");

        if (!passwordInput) return;

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    };


    const loginSubmitForm = document.querySelector(
        'form[action="../services/Controller/LoginController.php"]'
    );

    if (loginSubmitForm) {

        loginSubmitForm.addEventListener("submit", function () {

            const submitButton = loginSubmitForm.querySelector(
                'button[type="submit"]'
            );

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerText = "PROCESSANDO...";
            }

        });
    }

    setTimeout(() => {
        const sessionExpired = document.getElementById("session-expired");
        const mfaExpired = document.getElementById("mfa-expired");

        if (sessionExpired) sessionExpired.remove();
        if (mfaExpired) mfaExpired.remove();
    }, 6000);

});
