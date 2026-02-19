$(document).ready(function () {
  let clients, codClients;
  $("select").formSelect();
  M.updateTextFields();
  clients = $("#client.autocomplete").data("clientlist").split(",");

  codClients = $("#client.autocomplete").data("clientids").split(",");
  var obj = {};

  for (let i = 0; i < clients.length; i++) {
    obj[clients[i]] = null;
  }

  $("#client.autocomplete").autocomplete({
    data: obj,
  });

  $("#client.autocomplete").on("change", (e) => {
    console.log(clients);
    console.log(codClients);
    console.log(e.target.value);
    const index = clients.findIndex(
      (item) => item.split("-")[0] === e.target.value.split("-")[0]
    );
    if (index != -1) {
      console.log(index);
      $("#client-id").val(codClients[index]);
    }
  });

  $("#sel-type").on("change", function () {
    let s = $("#ncli");
    let t = $("#resp-sel");
    let per = $("#search_period");
    let dc = $("#dta-contract");
    let a = $("#start");
		let end = $("#end");
		let n = $("#new");
    $("#dta-contract").mask("00/00/0000");
    $("#date-start").mask("00/00/0000");
    $("#date-end").mask("00/00/0000");
    dc.addClass("invisible");
    dc.removeClass("visible");
    a.addClass("invisible");
    a.removeClass("visible");
    n.addClass("invisible");
    n.removeClass("visible");
    end.addClass("invisible");
    end.removeClass("visible");
    s.addClass("invisible");
    s.removeClass("visible");
    t.addClass("invisible");
    t.removeClass("visible");
    per.addClass("invisible");
    per.removeClass("visible");
    $("#" + $(this).val()).removeClass("invisible");
    $("#" + $(this).val()).addClass("visible");
  });

  $(".createUser").click(function (event) {
    const num = $("#num-proc");
    const cli = $("#client");
    if (cli.val().trim() == "" || num.val().trim() == "") {
      alert("Alguns campos estão incompletos!");
      event.preventDefault();
    }
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
        text: "Este registro de recepção será apagado definitivamente",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, deletar este registro!",
        cancelButtonText: "Não, cancelar!",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {
          swalWithBootstrapButtons.fire(
            "Deletando registro!",
            "O registro está sendo deletado, aguarde...",
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
