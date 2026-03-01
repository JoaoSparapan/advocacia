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
      text: "A exclusão desta providência não poderá ser revertida!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sim, Deletar esta providência!",
      cancelButtonText: "Não, Cancelar!",
      reverseButtons: true,
    })
    .then((result) => {
      if (result.isConfirmed) {
        swalWithBootstrapButtons.fire(
          "Deletando providência!",
          "Providencia esta sendo deletada, aguarde...",
          "success"
        );
        window.location.href = destination;
      }
    });
});

function requestEstimateTerm() {
  let dtaIntm = $("#data-publi").val();
  let qteDays = $("#qte-dias").val();
  let selDays = $("#sel-days").val();
  $("#sel-days-value").val(selDays);
  let stateHoliday = $("#state-holiday").is(":checked");
  let nationalHoliday = $("#national-holiday").is(":checked");

  if (idTimeOutTerm) clearTimeout(idTimeOutTerm);
  $("#prazo").val("Carregando dados...");

  idTimeOutTerm = setTimeout(() => {
    $.post(
      "../services/Controller/EstimateTerm.php",
      {
        "data-publi": dtaIntm,
        "sel-days": selDays,
        "qte-dias": qteDays,
        "state-holiday": stateHoliday ? 1 : 0,
        "national-holiday": nationalHoliday ? 1 : 0,
      },
      function (data, status) {
        $("#prazo").val(data);
      }
    );
  }, 500);
  M.updateTextFields();
}

function requestEstimateTermMobile() {
  let dtaIntm = $("#data-publi_mobile").val();
  let qteDays = $("#qte-dias_mobile").val();
  let selDays = $("#sel-days_mobile").val();
  let stateHoliday = $("#state-holiday").is(":checked");
  let nationalHoliday = $("#national-holiday").is(":checked");

  if (idTimeOutTerm) clearTimeout(idTimeOutTerm);
  $("#prazo").val("Carregando dados...");

  idTimeOutTerm = setTimeout(() => {
    $.post(
      "../services/Controller/EstimateTerm.php",
      {
        "data-publi": dtaIntm,
        "sel-days": selDays,
        "qte-dias": qteDays,
        "state-holiday": stateHoliday ? 1 : 0,
        "national-holiday": nationalHoliday ? 1 : 0,
      },
      function (data, status) {
        $("#prazo").val(data);
      }
    );
  }, 500);
  M.updateTextFields();
}

$("#data-publi").on("change", () => {
  requestEstimateTerm();
});
$("#state-holiday").on("change", () => {
  requestEstimateTerm();
});
$("#national-holiday").on("change", () => {
  requestEstimateTerm();
});
$("#qte-dias_mobile").on("change", () => {
  requestEstimateTermMobile();
});
$("#qte-dias").on("change", () => {
  requestEstimateTerm();
});
$("#sel-days_mobile").on("change", () => {
  requestEstimateTermMobile();
});
$("#sel-days").on("change", () => {
  requestEstimateTerm();
});

$(document).ready(function () {
  $("#date-start").mask("00/00/0000");
  $("#date-end").mask("00/00/0000");

  $("#sel-type").on("change", function () {
    let s = $("#ncli");
    let t = $("#vara-sel");
    let anid = $("#nproc");
    let per = $("#search_period");
    let ru = $("#responsible-user");

    // d.addClass('invisible')
    // d.removeClass('visible')
    s.addClass("invisible");
    s.removeClass("visible");
    per.addClass("invisible");
    per.removeClass("visible");
    t.addClass("invisible");
    t.removeClass("visible");
    ru.addClass("invisible");
    ru.removeClass("visible");
    anid.addClass("invisible");
    anid.removeClass("visible");
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
  $(".createProvidenceMobile").on("click", (event) => {

    document.getElementById('myform').addEventListener('submit', function(event) {
      var selectElement = document.getElementById('sel-days_mobile');
      var selectedValue = selectElement.value;
      
      if (selectedValue === '-1') {
          alert('Por favor, selecione o tipo da contagem de dias.');
          event.preventDefault(); // Impede o envio do formulário
      }
    });
    let dataInformada = new Date($("#data-intim_mobile").val()).toLocaleString(
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

    dtaAtual = dtaAtual.split(" ")[0];
    console.log("aquiii " + dataInformada);
    dataInformada = dataInformada.split(" ")[0];
    console.log("aquiii " + dataInformada.length);

    if (dataInformada.length < 10) {
      isLoad = false;
      alert("Data da intimação está em formato incorreto");
      event.preventDefault();
    }
    console.log("---");
  });
  $(".createProvidence").on("click", (event) => {

      document.getElementById('myform').addEventListener('submit', function(event){
        var selectElement = document.getElementById('sel-days');
        var selectedValue = selectElement.value;
        
        if (selectedValue === '-1') {
            alert('Por favor, selecione o tipo da contagem de dias.');
            event.preventDefault(); // Impede o envio do formulário
        }
      });
    let dataInformada = new Date($("#data-intim").val()).toLocaleString(
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

    dtaAtual = dtaAtual.split(" ")[0];
    console.log("aquiii " + dataInformada);
    dataInformada = dataInformada.split(" ")[0];
    console.log("aquiii " + dataInformada.length);

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
      adv: $("#select-adv").val()
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
          $("#adv").removeClass("invalid");
          let teste = $("#select-adv option:selected").text();
          let teste_mobile = $("#select-adv_mobile option:selected").text();
          $("#search_numProcess_inp").val(bodyData["num-proc"]);
          $("#search_numProcess_inp_mobile").val(bodyData["num-proc"]);
          $("#vara").val(vara);
          $("#cliente").val(bodyData["client"]);
          $("#adv").val(teste);
          $("#adv_mobile").val(teste_mobile);
          $("#vara_mobile").val(vara);
          $("#adv_mobile").val(teste);
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
    $("#adv_mobile").val("Carregando dados...");
    idTimeOut = setTimeout(() => {
      $.post(
        "../services/Controller/GetProcessByNum.php",
        { numproc: $("#search_numProcess_inp_mobile").val() },
        function (data, status) {
          let dados = JSON.parse(data);
          if (dados.length != 0) {
            $("#vara_mobile").val(dados[0].sigla);
            $("#cliente_mobile").val(dados[0].clientName);
            $("#adv_mobile").val(dados[0].name);
            $("#vara_mobile").removeClass("invalid");
            $("#cliente_mobile").removeClass("invalid");
            $("#adv_mobile").removeClass("invalid");
            $("#search_numProcess_inp_mobile").removeClass("invalid");
            $("#vara_mobile").addClass("valid");
            $("#cliente_mobile").addClass("valid");
            $("#adv_mobile").addClass("valid");
            $("#search_numProcess_inp_mobile").addClass("valid");
          } else {
            $("#vara_mobile").removeClass("valid");
            $("#cliente_mobile").removeClass("valid");
            $("#adv_mobile").removeClass("valid");
            $("#search_numProcess_inp_mobile").removeClass("valid");
            $("#vara_mobile").val("");
            $("#vara_mobile").addClass("invalid");
            $("#adv_mobile").val("");
            $("#adv_mobile").addClass("invalid");
            $("#cliente_mobile").val("");
            $("#cliente_mobile").addClass("invalid");
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
    $("#adv").val("Carregando dados...");
    idTimeOut = setTimeout(() => {
      $.post(
        "../services/Controller/GetProcessByNum.php",
        { numproc: $("#search_numProcess_inp").val() },
        function (data, status) {
          let dados = JSON.parse(data);
          if (dados.length != 0) {
            $("#vara").val(dados[0].sigla);
            $("#cliente").val(dados[0].clientName);
            $("#adv").val(dados[0].name);
            $("#vara").removeClass("invalid");
            $("#cliente").removeClass("invalid");
            $("#adv").removeClass("invalid");
            $("#search_numProcess_inp").removeClass("invalid");
            $("#vara").addClass("valid");
            $("#cliente").addClass("valid");
            $("#adv").addClass("valid");
            $("#search_numProcess_inp").addClass("valid");
          } else {
            $("#vara").removeClass("valid");
            $("#cliente").removeClass("valid");
            $("#adv").removeClass("valid");
            $("#search_numProcess_inp").removeClass("valid");
            $("#vara").val("");
            $("#vara").addClass("invalid");
            $("#adv").val("");
            $("#adv").addClass("invalid");
            $("#cliente").val("");
            $("#cliente").addClass("invalid");
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
    let doneProv = $(this).data("done");
    let providedProv = $(this).data("provided");

    $("#idProvidenceModal").val(idProvSelected);
    $("#provDoneModal")[0].checked = doneProv ? true : false;
    $("#provProvidedModal")[0].checked = providedProv ? true : false;
  });

  $(document).delegate(".deleteBtn", "click", async function (event) {
    event.preventDefault();

    let destination = $(this).prop("href");

    swalWithBootstrapButtons
      .fire({
        title: "Você tem certeza?",
        text: "Todas as providências serão apagadas!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, deletar esta providência!",
        cancelButtonText: "Não, cancelar!",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {
          swalWithBootstrapButtons.fire(
            "Deletando providência!",
            "A providência está sendo deletada, aguarde...",
            "success"
          );
          window.location.href = destination;
        }
      });
  });

  $("select").formSelect();
  var elems = document.querySelectorAll("select");
  var listOp = $("#search_numProcess_inp").data("listnproc").split(",");
  //console.log("teste " + listOp);
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

  function changeMobile() {
    const inputsComputer = $(".row.computer .input-field input");
    const inputsMobile = $(".row.mobile .input-field input");
    for (let i = 0; i < inputsComputer.length; i++) {
      inputsComputer[i].removeAttribute("required");
      inputsComputer[i].disabled = true;
      inputsMobile[i].setAttribute("required", "");
      inputsMobile[i].disabled = false;
    }
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

// Handle responsible person change
$(document).delegate(".updateResponsible", "change", function() {
  const selectedUserId = $(this).val();
  const processId = $(this).data("process");
  const $select = $(this);
  
  // Show loading state
  $select.prop("disabled", true);
  $select.css("opacity", "0.6");
  
  $.ajax({
    url: "../services/Controller/UpdateProvidenceResponsible.php",
    type: "POST",
    dataType: "json",
    data: {
      idProcess: processId,
      newUserId: selectedUserId
    },
    success: function(response) {
      $select.css("opacity", "1");
      if(response.status === 'success') {
        // Show success message
        Swal.fire({
          icon: 'success',
          title: 'Sucesso!',
          text: response.message,
          timer: 2000,
          showConfirmButton: false
        });
      } else {
        // Show error message and revert
        Swal.fire({
          icon: 'error',
          title: 'Erro!',
          text: response.message
        });
        location.reload();
      }
      $select.prop("disabled", false);
    },
    error: function(xhr, status, error) {
      $select.css("opacity", "1");
      let errorMessage = 'Erro ao atualizar responsável';
      try {
        const response = JSON.parse(xhr.responseText);
        errorMessage = response.message || errorMessage;
      } catch(e) {
        errorMessage = xhr.status + ': ' + error;
      }
      
      Swal.fire({
        icon: 'error',
        title: 'Erro!',
        text: errorMessage
      });
      $select.prop("disabled", false);
      // location.reload();
    }
  });
});
// M.FormSelect.init(document.querySelectorAll('#modalcreateProvidence select'));
$("#modalcreateProvidence").modal();
$("#modal3").modal();
$("#modalproc").modal();
