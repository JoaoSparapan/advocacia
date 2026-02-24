$("#nproc").mask("0000000-00.0000/00", {
  onKeyPress: function (v, event, currentField) {
    if (v.length >= 15 && v.length <= 18) {
      if (v[v.length - 1] == "/") {
        $("#nproc")[0].style.borderBottom = "1px solid red";
      } else {
        $("#nproc")[0].style.borderBottom = "none";
      }
    } else {
      if (
        (event.originalEvent.data + "" === "null" && v.length === 1) ||
        v === ""
      ) {
        $("#nproc")[0].style.borderBottom = "none";
      } else {
        $("#nproc")[0].style.borderBottom = "1px solid red";
      }
    }
  },
});

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

  $("select").formSelect();
  var elems = document.querySelectorAll("select");
});

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

$(document).delegate(".openModalStatus", "click", function (event) {
  let idProvSelected = $(this).data("prov");
  let providedProv = $(this).data("provided");
  let sf = $(this).data("sf");
  console.log(sf);
  let logs = $(this).attr("data-logs");
  logs = JSON.parse(logs);
  console.log(typeof logs);
  console.log(logs);
  let result = "";
  let phrase = "";
  let data = "";
  let hour = "";
  for (let i = 0; i < logs.length; i++) {
    data = logs[i][2].split(" ");
    hour = data[1];
    data = data[0].split("-");
    data = `${data[2]}/${data[1]}/${data[0]}`;
    if (logs[i][1] == 1) {
      phrase = `<p style="margin:0;">Status da petição alterado para <b style='color: green;'>Distribuída</b> por ${logs[i][5]}</p>`;
    }else if(logs[i][1] == 2)
    {
      phrase = `<p style="margin:0;">Status da petição alterado para <b style='color: red;'>Sem fundamento</b> por ${logs[i][5]}</p>`;
    } 
    else {
      phrase = `<p style="margin:0;">Status da petição alterado para <b>Não Distribuída</b> por ${logs[i][5]}</p>`;
    }

    result += `<div style="position: relative;border-radius: 0.3rem; padding: 0.5rem; background-color: #e0e0e0;"> 
    ${phrase}
    <p style="margin:0; position: relative; font-size: .7rem; text-align:end;color: #000000;">${data} às ${hour}</p>
    </div>`;
  }

  $("#content-logs").html(result);
  $("#idProvidenceModal").val(idProvSelected);
  $("#provProvidedModal")[0].checked = providedProv ? true : false;
  $("#sfProvidedModal")[0].checked = sf ? true : false;
});
$("select").formSelect();
$("#modal3").modal();
