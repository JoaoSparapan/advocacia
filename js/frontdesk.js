$(document).ready(function () {

  // -------------------- INICIALIZAÇÃO DO MATERIALIZE --------------------
  // Inicializa todos os selects da página
  $('select').formSelect();

  // -------------------- CONFIGURAÇÃO DINÂMICA DE LABELS --------------------

  const labelConfig = {

    maior: {
      nome: "Nome",
      nacionalidade: "Nacionalidade",
      estadoCivil: "Estado Civil",
      rg: "RG",
      cpf: "CPF ou CNI",
      profissao: "Profissão",
      endereco: "Endereço",
      bairro: "Bairro",
      cidade: "Cidade",
      cep: "CEP",
      estado: "Estado"
    },

    menor_pubere: {
      nome: "Nome do assistente",
      relacaoResponsavel: "Relação com o assistido",
      nacionalidade: "Nacionalidade do assistente",
      estadoCivil: "Estado Civil do assistente",
      rg: "RG do assistente",
      cpf: "CPF ou CNI do assistente",
      profissao: "Profissão do assistente",
      endereco: "Endereço do assistente",
      bairro: "Bairro do assistente",
      cidade: "Cidade do assistente",
      cep: "CEP do assistente",
      estado: "Estado do assistente",

      nomeDependente: "Nome do menor assistido",
      nacionalidadeDependente: "Nacionalidade do menor assistido",
      rgDependente: "RG do menor assistido",
      cpfDependente: "CPF do menor assistido"
    },

    menor_impubere: {
      nome: "Nome do representante",
      relacaoResponsavel: "Relação com o menor",
      nacionalidade: "Nacionalidade do representante",
      estadoCivil: "Estado Civil do representante",
      rg: "RG do representante",
      cpf: "CPF ou CNI do representante",
      profissao: "Profissão do representante",
      endereco: "Endereço do representante",
      bairro: "Bairro do representante",
      cidade: "Cidade do representante",
      cep: "CEP do representante",
      estado: "Estado do representante",

      nomeDependente: "Nome do menor",
      nacionalidadeDependente: "Nacionalidade do menor",
      rgDependente: "RG do menor",
      cpfDependente: "CPF do menor"
    },

    pj: {
      nome: "Nome do representante",
      relacaoResponsavel: "Relação com o menor",
      nacionalidade: "Nacionalidade do representante",
      estadoCivil: "Estado Civil do representante",
      rg: "RG do representante",
      cpf: "CPF ou CNI do representante",
      profissao: "Profissão do representante",
      endereco: "Endereço do representante",
      bairro: "Bairro do representante",
      cidade: "Cidade do representante",
      cep: "CEP do representante",
      estado: "Estado do representante"
    }

  };

  function updateLabels(tipo) {

    const config = labelConfig[tipo] || labelConfig["maior"];

    Object.keys(config).forEach(id => {
      $(`label[for="${id}"]`).text(config[id]);
    });

    // Mostrar ou ocultar campo relação
    if (tipo === "menor_pubere" || tipo === "menor_impubere") {
      $("#relacaoResponsavel").closest(".input-field").show();
    } else {
      $("#relacaoResponsavel").closest(".input-field").hide();
    }

  }

  // Executa ao mudar o select
  $("#situacao").on("change", function () {
    updateLabels($(this).val());
  });

  // Garante estado inicial
  updateLabels($("#situacao").val());

  // -------------------- FILTROS DE BUSCA --------------------
  $("#sel-type").on("change", function () {
    let s = $("#ncli"), t = $("#resp-sel"), per = $("#search_period"), adv = $("#adversa"), indicacao = $("#indicacaoF"), hibrida = $("#hibrida");
    let dc = $("#dta-contract"), a = $("#start"), end = $("#end"), n = $("#new");
    $("#dta-contract").mask("00/00/0000");
    $("#date-start").mask("00/00/0000");
    $("#date-end").mask("00/00/0000");
    [dc, a, n, end, s, t, per, adv, indicacao, hibrida].forEach(el => el.addClass("invisible").removeClass("visible"));
    $("#" + $(this).val()).removeClass("invisible").addClass("visible");
  });

  // -------------------- VALIDAÇÃO BÁSICA --------------------
  $(".createUser").click(function (event) {
    const num = $("#num-proc"), cli = $("#client");
    if (cli.val().trim() == "" || num.val().trim() == "") {
      alert("Alguns campos estão incompletos!");
      event.preventDefault();
    }
  });

  // -------------------- SWEETALERT DELETE --------------------
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: { confirmButton: "btn btn-success", cancelButton: "btn btn-danger" },
    buttonsStyling: false
  });

  $(document).delegate(".deleteUserBtn", "click", async function (event) {
    event.preventDefault();
    let destination = $(this).prop("href");
    swalWithBootstrapButtons.fire({
      title: "Você tem certeza?",
      text: "Este registro de atendimento será apagado definitivamente",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sim, deletar este registro!",
      cancelButtonText: "Não, cancelar!",
      reverseButtons: true
    }).then((result) => {
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

  // -------------------- MODAL E CAMPOS EXTRAS --------------------
  const modalElem = $("#modal2");
  const modalInstance = M.Modal.init(modalElem, {
    dismissible: true,
    opacity: 0.5,
    onOpenStart: function () {
      // Re-inicializa selects dentro do modal, caso existam
      $('select').formSelect();
    }
  });

  function toggleExtraFields() {
    const isChecked = $("#doc1").is(":checked");
    if (isChecked) $("#extra-fields").slideDown();
    else $("#extra-fields").slideUp();
  }

  $("#doc1").on("change", toggleExtraFields);

  $("#select-all-docs").on("change", function () {
    const checked = $(this).is(":checked");
    $(".doc-option").prop("checked", checked);
    toggleExtraFields();
  });

  $(".doc-option").on("change", function () {
    const allChecked = $(".doc-option").length === $(".doc-option:checked").length;
    $("#select-all-docs").prop("checked", allChecked);
    toggleExtraFields();
  });

  // -------------------- FORM SUBMIT PARA ZIP --------------------
  $("#modal2 form").on("submit", function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const clientName = $("#nome").val().replace(/\s+/g, "_"); // substitui espaços por _

    fetch(form.action, {
        method: form.method,
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error("Erro na resposta do servidor");
        return response.blob(); // recebe o ZIP
    })
    .then(blob => {
        const dateStr = new Date().toISOString().split("T")[0]; // YYYY-MM-DD
        const zipName = `documentos_${clientName}_${dateStr}.zip`;

        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = zipName;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);

        const instance = M.Modal.getInstance($("#modal2"));
        if (instance) instance.close();

        location.reload();
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Não foi possível gerar o ZIP'
        });
    });
  });

});
