

$(document).ready(function () {
  $(".createUser").click(function (event) {
    const name = $("#name");
    const email = $("#email-reg");
    if (
      email[0].className.indexOf("invalid") > 0 ||
      name.val().trim() == ""
    ) {
      alert("Alguns campos estão incorretos!");
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
        text: "Você não poderá desfazer essa ação!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, Deletar este usuário!",
        cancelButtonText: "Não, Cancelar!",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {
          swalWithBootstrapButtons.fire(
            "Deletando usuário!",
            "Usuário esta sendo deletado, aguarde...",
            "success"
          );
          window.location.href = destination;
        }
      });
  });

  $('select').formSelect();
  var elems = document.querySelectorAll('select');
  

  $("#modal2").modal();
});
