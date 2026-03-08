$(document).ready(function () {

  $('select').formSelect();
  M.updateTextFields(); // ativa labels preenchidas

  // -------------------- MOSTRAR/OCULTAR CLIENTE --------------------
  function toggleDadosCliente(tipo) {
    if (!tipo) {
      $("#dados-cliente-wrapper").hide();
    } else {
      $("#dados-cliente-wrapper").fadeIn(150);
    }
  }

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

    if (tipo === "menor_pubere" || tipo === "menor_impubere") {
      $("#relacaoResponsavel").closest(".input-field").show();
    } else {
      $("#relacaoResponsavel").closest(".input-field").hide();
    }

    M.updateTextFields();
  }

  // -------------------- TÍTULOS --------------------
  const cardTitleConfig = {
    maior: "Dados do cliente",
    menor_pubere: "Dados do assistente",
    menor_impubere: "Dados do representante",
    pj: "Dados do representante",
    litis: "Dados do litisconsorte"
  };

  function updateCardTitle(tipo) {
    const titulo = cardTitleConfig[tipo] || cardTitleConfig["maior"];
    $("#cliente-card-title").text(titulo);
  }

  function updateMenorTitle(tipo) {
    let titulo = "Dados do Menor";

    if (tipo === "menor_pubere") {
      titulo = "Dados do menor assistido";
    }

    if (tipo === "menor_impubere") {
      titulo = "Dados do menor";
    }

    $("#responsavel-fields .form-card-title").text(titulo);
  }

  // -------------------- CHANGE --------------------
  $("#situacao").on("change", function () {

    const tipo = $(this).val();

    updateLabels(tipo);
    updateCardTitle(tipo);
    updateMenorTitle(tipo);
    toggleDadosCliente(tipo);

    const responsavelFields = $("#responsavel-fields");
    const pjFields = $("#pj-fields");
    const litisFields = $("#litis-fields");

    const dependenteInputs = $("#responsavel-fields input");
    const pjInputs = $("#pj-fields input");
    const litisInputs = $("#litis-fields input");

    responsavelFields.hide();
    pjFields.hide();
    litisFields.hide();

    dependenteInputs.removeAttr("required");
    pjInputs.removeAttr("required");
    litisInputs.removeAttr("required");

    if (tipo === "menor_pubere" || tipo === "menor_impubere") {
      responsavelFields.fadeIn(150);
      dependenteInputs.attr("required", "required");
    }

    if (tipo === "pj") {
      pjFields.fadeIn(150);
      pjInputs.attr("required", "required");
    }

    if (tipo === "litis") {
      litisFields.fadeIn(150);
      litisInputs.attr("required", "required");
    }

  });

  // -------------------- ESTADO INICIAL (UPDATE) --------------------
  const tipoInicial = $("#situacao").val();

  updateLabels(tipoInicial);
  updateCardTitle(tipoInicial);
  updateMenorTitle(tipoInicial);
  toggleDadosCliente(tipoInicial);

  if (tipoInicial === "menor_pubere" || tipoInicial === "menor_impubere") {
    $("#responsavel-fields").show();
  }

  if (tipoInicial === "pj") {
    $("#pj-fields").show();
  }

  if (tipoInicial === "litis") {
    $("#litis-fields").show();
  }

});

function goBack() {
    window.history.back();
}