var idTimeOutTerm;
var idTimeOut;
function compareDates(dta1, dta2) {
  //informada, atual
  let d1 = parseInt(dta1[0]),
    d2 = parseInt(dta2[0]);
  let m1 = parseInt(dta1[1]),
    m2 = parseInt(dta2[1]);
  let y1 = parseInt(dta1[2]),
    y2 = parseInt(dta2[2]);

  if (y1 < y2) {
    return false;
  } else if (y1 > y2) {
    return true;
  }

  if (m1 < m2) {
    return false;
  } else if (m1 > m2) {
    return true;
  }

  if (d1 < d2) {
    return false;
  }

  return true;
}

$(document).delegate(".deleteProvicendeBtn", "click", async function (event) {
  event.preventDefault();

  let destination = $(this).prop("href");

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    buttonsStyling: false,
  });

  swalWithBootstrapButtons
    .fire({
      title: "Você tem certeza?",
      text: "A exclusão desta petição não poderá ser revertida!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sim, Deletar esta petição!",
      cancelButtonText: "Não, Cancelar!",
      reverseButtons: true,
    })
    .then((result) => {
      if (result.isConfirmed) {
        swalWithBootstrapButtons.fire(
          "Deletando petição!",
          "Petição esta sendo deletada, aguarde...",
          "success"
        );
        window.location.href = destination;
      }
    });
});

$(document).ready(function () {
  $("#sel-type").on("change", function () {
    let nc = $("#ncli");
    let a = $("#adverso");
    let dc = $("#dta-contract");
    let tc = $("#type-action");
    let ru = $("#responsible-user");
    let per = $("#search_period");
    let dtt = $("#days-to-trial");
    $("#dta-contract").mask("00/00/0000");
    $("#date-start").mask("00/00/0000");
    $("#date-end").mask("00/00/0000");
    nc.addClass("invisible");
    nc.removeClass("visible");
    per.addClass("invisible");
    per.removeClass("visible");
    a.addClass("invisible");
    a.removeClass("visible");
    dc.addClass("invisible");
    dc.removeClass("visible");
    tc.addClass("invisible");
    tc.removeClass("visible");
    ru.addClass("invisible");
    ru.removeClass("visible");
    dtt.addClass("invisible");
    dtt.removeClass("visible");
    $("#" + $(this).val()).removeClass("invisible");
    $("#" + $(this).val()).addClass("visible");
  });
  $("select").formSelect();
  $("#data-publi_mobile").on("change", function () {
    let dataInformada = new Date($("#data-intim_mobile").val()).toLocaleString(
      "pt-BR",
      {
        timeZoneName: "longOffset",
        timeZone: "America/Sao_Paulo",
      }
    );

    let dataInfo = new Date($("#data-publi_mobile").val()).toLocaleString(
      "pt-BR",
      {
        timeZoneName: "longOffset",
        timeZone: "America/Sao_Paulo",
      }
    );

    let dtaAtual = new Date().toLocaleString("pt-BR", {
      timeZoneName: "longOffset",
      timeZone: "America/Sao_Paulo",
    });

    let dataInfo2 = new Date().toLocaleString("pt-BR", {
      timeZoneName: "longOffset",
      timeZone: "America/Sao_Paulo",
    });

    dtaAtual = dtaAtual.split(" ")[0];
    dataInformada = dataInformada.split(" ")[0];

    dataInfo2 = dataInfo2.split(" ")[0];
    dataInfo = dataInfo.split(" ")[0];
    $("#data-intim_mobile").removeClass("valid");
    $("#data-intim_mobile").removeClass("invalid");

    $("#data-publi_mobile").removeClass("valid");
    $("#data-publi_mobile").removeClass("invalid");
  });
  $("#data-publi").on("change", function () {
    let dataInformada = new Date($("#data-intim").val()).toLocaleString(
      "pt-BR",
      {
        timeZoneName: "longOffset",
        timeZone: "America/Sao_Paulo",
      }
    );

    let dataInfo = new Date($("#data-publi").val()).toLocaleString("pt-BR", {
      timeZoneName: "longOffset",
      timeZone: "America/Sao_Paulo",
    });

    let dtaAtual = new Date().toLocaleString("pt-BR", {
      timeZoneName: "longOffset",
      timeZone: "America/Sao_Paulo",
    });

    let dataInfo2 = new Date().toLocaleString("pt-BR", {
      timeZoneName: "longOffset",
      timeZone: "America/Sao_Paulo",
    });

    dtaAtual = dtaAtual.split(" ")[0];
    dataInformada = dataInformada.split(" ")[0];

    dataInfo2 = dataInfo2.split(" ")[0];
    dataInfo = dataInfo.split(" ")[0];
    $("#data-intim").removeClass("valid");
    $("#data-intim").removeClass("invalid");

    $("#data-publi").removeClass("valid");
    $("#data-publi").removeClass("invalid");
  });
  function getDifferenceInDays(date1, date2) {
    const diffInMs = Math.abs(date2 - date1);
    return diffInMs / (1000 * 60 * 60 * 24);
  }
  $(".createProvidenceMobile").on("click", (event) => {
    if ($("#data-intim_mobile").val() == "") {
      isLoad = false;
      return;
    }
    if ($("#client_name_mobile").val() == "") {
      isLoad = false;
      return;
    }
    if ($("#adverse_mobile").val() == "") {
      isLoad = false;
      return;
    }

    if ($("#select-adv_mobile").val() == null) {
      alert("Selecione um advogado");
      isLoad = false;
      return;
    }
    if ($("#typeAction_mobile").val() == "") {
      isLoad = false;
      return;
    }
    var dataInformada = $("#data-intim_mobile").val() + " 00:00:00";

    var dataAtual = new Date();
    dataAtual.setHours(0, 0, 0);
    dataInformada = new Date(dataInformada);
    if (dataInformada.getTime() > dataAtual.getTime()) {
      isLoad = false;
    }

    if (dataInformada.length < 10) {
      isLoad = false;
      alert("Data da intimação está em formato incorreto");
      event.preventDefault();
    }
  });
  $(".createPetition").on("click", (event) => {
    if ($("#data-intim").val() == "") {
      isLoad = false;
      return;
    }
    if ($("#client").val() == "") {
      isLoad = false;
      return;
    }
    if ($("#adverse").val() == "") {
      isLoad = false;
      return;
    }

    if ($("#select-adv").val() == null) {
      alert("Selecione um advogado");
      isLoad = false;
      return;
    }
    if ($("#typeAction").val() == "") {
      isLoad = false;
      return;
    }
    var dataInformada = $("#data-intim_mobile").val() + " 00:00:00";

    var dataAtual = new Date();
    dataAtual.setHours(0, 0, 0);
    dataInformada = new Date(dataInformada);
    if (dataInformada.getTime() > dataAtual.getTime()) {
      isLoad = false;
    }

    if (dataInformada.length < 10) {
      isLoad = false;
      alert("Data da intimação está em formato incorreto");
      event.preventDefault();
    }
  });
  $("#search_numProcess_inp_mobile").on("keyup", function () {
    $("#addprov_mobile").removeClass("invisible");
  });
  $("#search_numProcess_inp").on("keyup", function () {
    $("#addprov").removeClass("invisible");
  });
  $("#addprov_mobile").on("click", function () {
    $("#num-proc").val($("#search_numProcess_inp_mobile").val());
  });
  $("#addprov").on("click", function () {
    $("#num-proc").val($("#search_numProcess_inp").val());
  });
  $(".createProcInProvidence").on("click", function () {
    var bodyData = {
      "num-proc": $("input#num-proc").val(),
      client: $("input#client").val(),
    };
    var vara = "";
    if ($("#select-vara").val()) {
      vara = $("#select-vara option:selected").html();
      bodyData["court"] = $("#select-vara").val();
    } else if ($("#new-vara-id").val()) {
      vara = $("#new-vara-id").val();
      bodyData["new-vara"] = $("#new-vara-id").val();
    } else {
      alert("Preencha todos os campos!");
      return;
    }

    $.post(
      "../services/Controller/CreateProccess.php",
      bodyData,
      function (data, status) {
        const response = data.split("<br/><br/>")[0];
        if (response.trim() === "Sucesso ao cadastrar processo!") {
          $("#vara").removeClass("invalid");
          $("#cliente").removeClass("invalid");
          console.log(vara);
          $("#search_numProcess_inp").val(bodyData["num-proc"]);
          $("#search_numProcess_inp_mobile").val(bodyData["num-proc"]);
          $("#vara").val(vara);
          $("#cliente").val(bodyData["client"]);
          $("#vara_mobile").val(vara);
          $("#cliente_mobile").val(bodyData["client"]);
          var listOp = $("#search_numProcess_inp").data("listnproc").split(",");

          listOp.push(bodyData["num-proc"]);
          var obj = {};

          for (let i = 0; i < listOp.length; i++) {
            obj[listOp[i]] = null;
          }

          $("#search_numProcess_inp.autocomplete").autocomplete({
            data: obj,
          });
          $("#search_numProcess_inp_mobile.autocomplete").autocomplete({
            data: obj,
          });
          M.updateTextFields();

          Swal.fire({
            icon: "success",
            title: "Sucesso",
            text: response,
            confirmButtonText: "Ok",
          }).then((result) => {
            if (result.isConfirmed) {
              console.log("---");
              $("#modalproc").modal("close");
            }
          });
        } else if (response.trim() === "Selecione a vara") {
          Swal.fire({
            icon: "error",
            title: "Erro",
            text: "Preencha todos os campos",
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Erro",
            text: response,
          });
        }
      }
    );
  });
  $("#search_numProcess_inp_mobile").on("change", () => {
    if (idTimeOut) clearTimeout(idTimeOut);
    $("#vara_mobile").val("Carregando dados...");
    $("#cliente_mobile").val("Carregando dados...");
    // $("#addprov").hasClass("invisible")
    //   ? ""
    //   : $("#addprov").addClass("invisible");
    idTimeOut = setTimeout(() => {
      $.post(
        "../services/Controller/GetProcessByNum.php",
        { numproc: $("#search_numProcess_inp_mobile").val() },
        function (data, status) {
          let dados = JSON.parse(data);
          if (dados.length != 0) {
            $("#vara_mobile").val(dados[0].sigla);
            $("#cliente_mobile").val(dados[0].clientName);
            $("#vara_mobile").removeClass("invalid");
            $("#cliente_mobile").removeClass("invalid");
            $("#search_numProcess_inp_mobile").removeClass("invalid");
            $("#vara_mobile").addClass("valid");
            $("#cliente_mobile").addClass("valid");
            $("#search_numProcess_inp_mobile").addClass("valid");
          } else {
            $("#vara_mobile").removeClass("valid");
            $("#cliente_mobile").removeClass("valid");
            $("#search_numProcess_inp_mobile").removeClass("valid");
            $("#vara_mobile").val("");
            $("#vara_mobile").addClass("invalid");
            $("#cliente_mobile").val("");
            $("#cliente_mobile").addClass("invalid");

            // $("#addprov").removeClass("invisible");
            //$("#search_numProcess_inp").addClass("invalid");

            M.updateTextFields();
          }
        }
      );
    }, 500);
    M.updateTextFields();
  });

  $("#search_numProcess_inp").on("change", () => {
    if (idTimeOut) clearTimeout(idTimeOut);
    $("#vara").val("Carregando dados...");
    $("#cliente").val("Carregando dados...");
    // $("#addprov").hasClass("invisible")
    //   ? ""
    //   : $("#addprov").addClass("invisible");
    idTimeOut = setTimeout(() => {
      $.post(
        "../services/Controller/GetProcessByNum.php",
        { numproc: $("#search_numProcess_inp").val() },
        function (data, status) {
          let dados = JSON.parse(data);
          if (dados.length != 0) {
            $("#vara").val(dados[0].sigla);
            $("#cliente").val(dados[0].clientName);
            $("#vara").removeClass("invalid");
            $("#cliente").removeClass("invalid");
            $("#search_numProcess_inp").removeClass("invalid");
            $("#vara").addClass("valid");
            $("#cliente").addClass("valid");
            $("#search_numProcess_inp").addClass("valid");
          } else {
            $("#vara").removeClass("valid");
            $("#cliente").removeClass("valid");
            $("#search_numProcess_inp").removeClass("valid");
            $("#vara").val("");
            $("#vara").addClass("invalid");
            $("#cliente").val("");
            $("#cliente").addClass("invalid");

            // $("#addprov").removeClass("invisible");
            //$("#search_numProcess_inp").addClass("invalid");

            M.updateTextFields();
          }
        }
      );
    }, 500);
    M.updateTextFields();
  });

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    buttonsStyling: false,
  });

  $(document).delegate(".openModalStatus", "click", function (event) {
    let idProvSelected = $(this).data("prov");
    let providedProv = $(this).data("provided");

    $("#idProvidenceModal").val(idProvSelected);
    $("#provProvidedModal")[0].checked = providedProv ? true : false;
  });

  $(document).delegate(".deleteBtn", "click", async function (event) {
    event.preventDefault();

    let destination = $(this).prop("href");

    swalWithBootstrapButtons
      .fire({
        title: "Você tem certeza?",
        text: "Todas as petições serão apagadas!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, deletar esta petição!",
        cancelButtonText: "Não, cancelar!",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {
          swalWithBootstrapButtons.fire(
            "Deletando petição!",
            "A petição está sendo deletada, aguarde...",
            "success"
          );
          window.location.href = destination;
        }
      });
  });

  $("select").formSelect();

  function changeMobile() {
    const inputsComputer = $(".row.computer .input-field input");
    const inputsMobile = $(".row.mobile .input-field input");
    for (let i = 0; i < inputsComputer.length; i++) {
      inputsComputer[i].removeAttribute("required");
      inputsComputer[i].disabled = true;
      inputsMobile[i].setAttribute("required", "");
      inputsMobile[i].disabled = false;
    }

    $(".row.computer .input-field select")[0].removeAttribute("required");
    $(".row.computer .input-field select").disabled = true;
    $(".row.mobile .input-field select")[0].setAttribute("required", "");
    $(".row.mobile .input-field select").disabled = false;
  }
  function changeComputer() {
    const inputsMobile = $(".row.mobile .input-field input");
    const inputsComputer = $(".row.computer .input-field input");

    for (let i = 0; i < inputsMobile.length; i++) {
      inputsMobile[i].removeAttribute("required");
      inputsMobile[i].disabled = true;
      inputsComputer[i].setAttribute("required", "");
      inputsComputer[i].disabled = false;
    }
    $(".row.mobile .input-field select")[0].removeAttribute("required");
    $(".row.mobile .input-field select").disabled = true;
    $(".row.computer .input-field select")[0].setAttribute("required", "");
    $(".row.computer .input-field select").disabled = false;
  }
  window.addEventListener("resize", () => {
    if (window.innerWidth <= 750) {
      changeMobile();
    } else {
      changeComputer();
    }
  });

  if (window.innerWidth <= 750) {
    changeMobile();
  } else {
    changeComputer();
  }
});

$("#modalcreateProvidence").modal();
$("#modal3").modal();
$("#modalproc").modal();
