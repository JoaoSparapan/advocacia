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

  $("select").formSelect();
  var elems = document.querySelectorAll("select");
});
$("#sel-type").on("change", function () {
  let s = $("#ncli");
  let t = $("#vara-sel");
  let anid = $("#nproc");
  let per = $("#search_period");
  let ru = $("#responsible-user");
  $("#date-start").mask("00/00/0000");
  $("#date-end").mask("00/00/0000");

  ru.addClass("invisible");
  ru.removeClass("visible");
  s.addClass("invisible");
  s.removeClass("visible");
  per.addClass("invisible");
  per.removeClass("visible");
  t.addClass("invisible");
  t.removeClass("visible");
  anid.addClass("invisible");
  anid.removeClass("visible");
  $("#" + $(this).val()).removeClass("invisible");
  $("#" + $(this).val()).addClass("visible");
});

$(document).delegate(".openModalStatus", "click", function (event) {
  let idProvSelected = $(this).data("prov");
  let doneProv = $(this).data("done");
  let providedProv = $(this).data("provided");
  console.log(idProvSelected);
  $("#idProvidenceModal").val(idProvSelected);
  $("#provDoneModal")[0].checked = doneProv ? true : false;
  $("#provProvidedModal")[0].checked = providedProv ? true : false;
});
$("select").formSelect();
$("#modal3").modal();
