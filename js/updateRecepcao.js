function goBack() {
    window.history.back();
}

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
    
    $("select").formSelect();
    var elems = document.querySelectorAll("select");
  
  });
  