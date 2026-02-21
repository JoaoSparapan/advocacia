$(document).ready(function () {
  $("#sel-type").on("change", function () {
    let s = $("#ncli");
    let t = $("#vara-sel");
    let anid = $("#num-proc2");
    let d = $("#responsible-user");

    d.addClass('invisible');
    d.removeClass('visible');
    s.addClass("invisible");
    s.removeClass("visible");
    t.addClass("invisible");
    t.removeClass("visible");
    anid.addClass("invisible");
    anid.removeClass("visible");
    $("#" + $(this).val()).removeClass("invisible");
    $("#" + $(this).val()).addClass("visible");
  });

  $(".new-vara").click(function () {
    $(this).toggleClass("active");
    $("#new-vara-id").toggleClass("invisible");
    $("#select-vara").parent().toggleClass("invisible");
    $("#select-vara").val("");
  });

  $(".createUser").click(function (event) {
    const num = $("#num-proc");
    const cli = $("#client");
    if (cli.val().trim() == "" || num.val().trim() == "") {
      alert("Alguns campos estão incompletos!");
      event.preventDefault();
    }
  });

  $("#select-vara").on("change", function (event) {
    $(".deleteVara").addClass("visible");
  });
  $(".deleteVara").on("click", function () {
    // fazer requisição ajax para deletar

    $.post(
      "../services/Controller/DeleteCourt.php",
      {
        idvara: $("#select-vara").val(),
      },
      function (data, status) {
        if (data.trim() === "Sucesso ao deletar vara") {
          $(".select-wrapper ul li.selected").remove();
          $(".select-wrapper input").val("");
          $("#select-vara").val("");
          Swal.fire({
            icon: "success",
            title: "Sucesso",
            text: `${data}`,
          });
          $(".deleteVara").removeClass("visible");
        } else {
          Swal.fire({
            icon: "error",
            title: "Erro",
            text: `${data}`,
          });
        }
      }
    );
  });
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    buttonsStyling: false,
  });

  $(document).delegate(".deleteUserBtn", "click", async function (event) {
    event.preventDefault();

    let destination = $(this).prop("href");

    swalWithBootstrapButtons
      .fire({
        title: "Você tem certeza?",
        text: "Todas as providências deste processo também serão apagadas!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, deletar este processo!",
        cancelButtonText: "Não, cancelar!",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {
          swalWithBootstrapButtons.fire(
            "Deletando processo!",
            "O processo está sendo deletado, aguarde...",
            "success"
          );
          window.location.href = destination;
        }
      });
  });

  $("select").formSelect();
  var elems = document.querySelectorAll("select");

  $("#modal2").modal();
});
