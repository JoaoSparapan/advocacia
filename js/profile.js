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
  $(".toggleUpdate").click(function () {
    // $(this)[0].style.display="none";
    // console.log($(this)[0].innerHTML)
    let el = $(".in");
    el.toggleClass("invisible");
    if (
      $(this)[0].innerHTML == '<i class="fa-solid fa-chevron-left"></i> Voltar'
    ) {
      $(this).html('<i class="fa-solid fa-pen"></i> Editar Informações');
    } else {
      $(this).html('<i class="fa-solid fa-chevron-left"></i> Voltar');
    }
    if (
      $(this)[0].innerHTML ==
      '<i class="fa-solid fa-pen"></i> Editar Informações'
    ) {
      $(this).html('<i class="fa-solid fa-pen"></i> Editar Informações');
    } else {
      $(this).html('<i class="fa-solid fa-chevron-left"></i> Voltar');
    }

    M.updateTextFields();
  });

  $("#cpf").mask("000.000.000-00", {
    onChange: function (v) {
      let cpf = $("#cpf").cleanVal();
      //console.log(cpf)
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
      //console.log($('#icon_telephone').val())
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

  $(".updateProfile").click(function (event) {
    const nome = $("#nome").val();
    const cpf = $("#cpf").val();
    const email = $("#email").val();
    const senhaAnt = $("#passant").val();
    const novaSenha = $("#passagr").val();
    const confirmSenha = $("#passagrconfirm").val();

    if(!validarCPF(cpf))
    {
      alert("Insira um CPF válido!");
      event.preventDefault();
      return;
    }
    if (senhaAnt.trim() === "") {
      alert("Por favor informe a senha atual!");
      event.preventDefault();
      return;
    }

    if (nome.trim() === "" || phone.trim() === "" || email.trim() === "") {
      alert("Por favor informe todos os campos!");
      event.preventDefault();
      return;
    } else {
      if (novaSenha != confirmSenha) {
        alert("A nova senha e sua confirmação não conferem!");
        event.preventDefault();
        return;
      }
    }

    $(".toggleUpdate").prop("disabled", true);
    $(this).text("Carregando...");
    console.log("asd");
    // event.preventDefault();
  });

  // M.textareaAutoResize($('#textarea1'));
});