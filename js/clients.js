function validarCPF(cpf) {	
	cpf = cpf.replace(/[^\d]+/g,'');	
	if(cpf == '') return false;	
	// Elimina CPFs invalidos conhecidos	
	if (cpf.length != 11 || 
		cpf == "00000000000" || 
		cpf == "11111111111" || 
		cpf == "22222222222" || 
		cpf == "33333333333" || 
		cpf == "44444444444" || 
		cpf == "55555555555" || 
		cpf == "66666666666" || 
		cpf == "77777777777" || 
		cpf == "88888888888" || 
		cpf == "99999999999")
			return false;		
	// Valida 1o digito	
	add = 0;	
	for (i=0; i < 9; i ++)		
		add += parseInt(cpf.charAt(i)) * (10 - i);	
		rev = 11 - (add % 11);	
		if (rev == 10 || rev == 11)		
			rev = 0;	
		if (rev != parseInt(cpf.charAt(9)))		
			return false;		
	// Valida 2o digito	
	add = 0;	
	for (i = 0; i < 10; i ++)		
		add += parseInt(cpf.charAt(i)) * (11 - i);	
	rev = 11 - (add % 11);	
	if (rev == 10 || rev == 11)	
		rev = 0;	
	if (rev != parseInt(cpf.charAt(10)))
		return false;		
	return true;   
}

$(document).ready(function(){
	$("#sel-type").on("change", function () {
		let a = $("#start");
		let b = $("#title");
		let dc = $("#end");
		let n = $("#new");
		a.addClass("invisible");
		a.removeClass("visible");
		n.addClass("invisible");
		n.removeClass("visible");
		b.addClass("invisible");
		b.removeClass("visible");
		dc.addClass("invisible");
		dc.removeClass("visible");
		$("#" + $(this).val()).removeClass("invisible");
		$("#" + $(this).val()).addClass("visible");
	  });
	  $("select").formSelect();

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
			text: "Todos os processos e providências deste cliente serão deletados também!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Sim, Deletar este cliente!",
			cancelButtonText: "Não, Cancelar!",
			reverseButtons: true,
		  })
		  .then((result) => {
			if (result.isConfirmed) {
			  swalWithBootstrapButtons.fire(
				"Deletando usuário!",
				"Cliente esta sendo deletado, aguarde...",
				"success"
			  );
			  window.location.href = destination;
			}
		  });
	  });

    $('#cpf').mask('000.000.000-00', {
            onChange: function(v){

                let cpf = $('#cpf').cleanVal();
                console.log(cpf)
                if(validarCPF(cpf)){
                    $('#cpf').removeClass('invalid')
                    $('#cpf').addClass('valid')
                }else{
                    $('#cpf').removeClass('valid')
                    $('#cpf').addClass('invalid')
                }
                if($('#cpf').val().length==0){
                    $('#cpf').removeClass('invalid')
                    $('#cpf').removeClass('valid')
                }
                
            }
            
        }
    );

// M.textareaAutoResize($('#textarea1'));
$("#modal2").modal();
})