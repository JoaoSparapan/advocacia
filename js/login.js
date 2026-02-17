var loadingElement = null;
$(document).delegate(".loading", "click", function () {
  var count = 0;
  loadingElement = $(this);
  setInterval(function () {
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
});

document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('form[action="../services/Controller/LoginController.php"]');
    const overlay = document.getElementById('loading-overlay');
    const submitButton = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function() {
        overlay.style.display = 'flex';
        submitButton.disabled = true;
    });
});

function validarCPF(cpf) {
  cpf = cpf.replace(/[^\d]+/g, "");
  if (cpf == "") return false;
  // Elimina CPFs invalidos conhecidos
  if (
    cpf.length != 11 ||
    cpf == "00000000000" ||
    cpf == "11111111111" ||
    cpf == "22222222222" ||
    cpf == "33333333333" ||
    cpf == "44444444444" ||
    cpf == "55555555555" ||
    cpf == "66666666666" ||
    cpf == "77777777777" ||
    cpf == "88888888888" ||
    cpf == "99999999999"
  )
    return false;
  // Valida 1o digito
  add = 0;
  for (i = 0; i < 9; i++) add += parseInt(cpf.charAt(i)) * (10 - i);
  rev = 11 - (add % 11);
  if (rev == 10 || rev == 11) rev = 0;
  if (rev != parseInt(cpf.charAt(9))) return false;
  // Valida 2o digito
  add = 0;
  for (i = 0; i < 10; i++) add += parseInt(cpf.charAt(i)) * (11 - i);
  rev = 11 - (add % 11);
  if (rev == 10 || rev == 11) rev = 0;
  if (rev != parseInt(cpf.charAt(10))) return false;
  return true;
}

$(document).ready(function () {
  let Log = $(".log");
  let Register = $(".register");
  let Forg = $(".forg");
  let extra = $(".extra");

  $(".toggleForm").click(function () {
    Forg.removeClass("active");

    let f = $(this).data("form");
    if (f == "register") {
      Log.removeClass("active");
      Register.addClass("active");
      extra.removeClass("active");
    } else {
      Register.removeClass("active");
      Log.addClass("active");
      extra.addClass("active");
    }
  });

  $(".forgot").click(function () {
    Log.removeClass("active");
    Register.removeClass("active");
    Forg.toggleClass("active");
    extra.removeClass("active");
  });

  $("#cpf").mask("000.000.000-00", {
    onChange: function (v) {
      let cpf = $("#cpf").cleanVal();

      if (validarCPF(cpf)) {
        $("#cpf").removeClass("invalid");
        $("#cpf").addClass("valid");
      } else {
        $("#cpf").removeClass("valid");
        $("#cpf").addClass("invalid");
      }
      if ($("#cpf").val().length == 0) {
        $("#cpf").removeClass("invalid");
        $("#cpf").removeClass("valid");
      }
    },
  });
  $("#icon_telephone").mask("(00) 00000-0000", {
    onChange: function (v) {
      if ($("#icon_telephone").val().length == 15) {
        $("#icon_telephone").addClass("valid");
        $("#icon_telephone").removeClass("invalid");
      } else {
        $("#icon_telephone").addClass("invalid");
        $("#icon_telephone").removeClass("valid");
        if ($("#icon_telephone").val().length == 0) {
          $("#icon_telephone").removeClass("invalid");
          $("#icon_telephone").removeClass("valid");
        }
      }
    },
  });
  //https://sweetalert2.github.io/#examples
  $("#reg-user").click(function (e) {
    let phone = $("#icon_telephone");
    let cpf = $("#cpf");
    let email = $("#email-reg");

    if (
      Array.from(phone[0].classList).indexOf("invalid") != -1 ||
      Array.from(cpf[0].classList).indexOf("invalid") != -1 ||
      Array.from(email[0].classList).indexOf("invalid") != -1
    ) {
      e.preventDefault();
      Swal.fire({
        icon: "error",
        title: "Oops...",
        showCloseButton: true,
        text: "Alguns campos não estão preenchidos corretamente!",
        showConfirmButton: false,
        timer: 5000,
      });
    }
  });
});
