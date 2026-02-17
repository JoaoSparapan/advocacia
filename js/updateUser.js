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
function goBack() {
    window.history.back()
}

$(document).ready(function(){

    

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


$(".updateInfoUser").click(function(event){
    
    const nome = $("#name").val();
    const cpf = $("#cpf").val();
    const email = $("#email-reg").val();
    
    if(!validarCPF(cpf))
    {
      alert("Insira um CPF válido!");
      event.preventDefault();
      return;
    }
    
    if (nome.trim()==="" || 
    cpf.trim()==="" || 
    email.trim()===""){
        alert("Por favor informe todos os campos!")
        event.preventDefault();
        return ;
    }

   
    $(this).text("Alterando usuário...")

    //event.preventDefault();
})

// M.textareaAutoResize($('#textarea1'));

})