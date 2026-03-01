function validarCPF(cpf) {	
	cpf = cpf.replace(/[^\d]+/g,'');	
	if(cpf == '') return false;	

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

	let add = 0;	
	for (let i=0; i < 9; i++)		
		add += parseInt(cpf.charAt(i)) * (10 - i);	

	let rev = 11 - (add % 11);	
	if (rev == 10 || rev == 11)		
		rev = 0;	

	if (rev != parseInt(cpf.charAt(9)))		
		return false;		

	add = 0;	
	for (let i = 0; i < 10; i++)		
		add += parseInt(cpf.charAt(i)) * (11 - i);	

	rev = 11 - (add % 11);	
	if (rev == 10 || rev == 11)	
		rev = 0;	

	if (rev != parseInt(cpf.charAt(10)))
		return false;		

	return true;   
}

function goBack() {
    window.history.back();
}

$(document).ready(function(){

    // Inicializa select do Materialize
    $('select').formSelect();

    // Máscara CPF
    $('#cpf').mask('000.000.000-00', {
        onChange: function(){

            let cpf = $('#cpf').cleanVal();

            if(validarCPF(cpf)){
                $('#cpf').removeClass('invalid').addClass('valid');
            }else{
                $('#cpf').removeClass('valid').addClass('invalid');
            }

            if($('#cpf').val().length == 0){
                $('#cpf').removeClass('invalid valid');
            }
        }
    });

    $(".updateInfoUser").click(function(event){
        
        const nome = $("#name").val();
        const cpfMask = $("#cpf").val();
        const cpf = cpfMask.replace(/[^\d]+/g,'');
        const email = $("#email-reg").val();
        const role = $("#select-role").val();

        if(!validarCPF(cpf)){
            alert("Insira um CPF válido!");
            event.preventDefault();
            return;
        }
        
        if (nome.trim()==="" || 
            cpf.trim()==="" || 
            email.trim()==="" ||
            role === null || role === "-1"){

            alert("Por favor informe todos os campos!");
            event.preventDefault();
            return;
        }

        $(this).html('<i class="fa-solid fa-spinner fa-spin"></i> Alterando...');
    });

});