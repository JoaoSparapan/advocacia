<?php

class UserMail{
   
    private $quant;

    function __construct($quant){
        $this->quant = $quant;
    }

    public function content(){
        $content = '
    <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <style>
        body{
            background-color: whitesmoke;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100vh;
            text-align: center;
        }
        .logo{
            display: block;
            color: #3e64ff;
            font-weight: 900;
            margin: 20px 0px;
            -webkit-transition: 0.3s;
            -o-transition: 0.3s;
            transition: 0.3s;
            text-align: center;
        }
        .btn {
            padding: 10px;
            margin: 10px;
            background-color: #3e64ff !important;
            width: 80%;
            border-radius: 20px !important;
        }
        </style>
</head>

<body>

    <div class="logIn z-depth-1">
        <h1 class="logo"> Advocacia Bertoldi </h1>
        <div>
            
            <div class="">
                <div class="row">
                    <form class="col s12">
                        <div class="row">
                            <div class="row">
                                <div class="col s10">
                                    <br><br><h3 style="color:red;">ATENÇÃO!</h3>
                                    <h5 style="margin: 0;">Há '.$this->quant.' providência(s) pendente(s) em nosso sistema com o prazo atrasado e/ou encerrando hoje!</h5>
                                    <h5 style="margin: 0;"><a href="https://providencia.advbertoldi.com.br/">Clique aqui</a> para acessar o sistema agora mesmo</h5>
                                </div>
                            </div>

                        </div>

                    </form>
                    
                </div>
            </div>

        </div>


        
</body>

</html>
        
        ';
    return $content;
    }
}