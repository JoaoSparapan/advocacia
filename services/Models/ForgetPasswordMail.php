<?php

class ForgetPasswordMail{
    private $pass;
    private $title;

    function __construct($pass='', $title='Recuperação de senha'){
        $this->pass = $pass;
        $this->title = $title;
        
    }

    public function content(){
        $content = '
        <html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Recuperação de senha</title>

    <style>
      body {
        background-color: whitesmoke;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100vh;
        flex-direction: column;
      }

      .logo {
        display: block;
        color: #3e64ff;
        font-weight: 900;
        margin: 20px 0px;
        -webkit-transition: 0.3s;
        -o-transition: 0.3s;
        transition: 0.3s;
        text-align: center;
        flex-direction: column;
      }

      .btn {
        padding: 10px;
        margin: 10px;
        background-color: #3e64ff !important;
        width: 80%;
        border-radius: 20px !important;
        text-align: center;
        flex-direction: column;
      }

      .logIn {
        padding: 10px;
        background-color: white;
        width: 300px;
        height: 350px;
        
        justify-content: space-around;
        align-items: center;
        
      }
      .logIn div {
        width: 100%;
        
      }
    </style>
  </head>

  <div class="logIn z-depth-1">
        <h1 class="logo"> '.$this->title.'</h1>
        <div>
            
            <div class="">
                <div class="row">
                    <form class="col s12">
                        <div class="row">
                            <div class="row">
                                <div class="col s10">
                                    <h5 style="margin: 0;">Aqui está sua nova senha para acessar nosso sistema:</h5><br>
                                    <h5 style="margin: 0;">Senha:'.$this->pass.'</h5><br><br>
                                    <h5 style="margin: 0;">Lembre-se de alterá-la acessando a aba do seu perfil!</h5><br>
                                </div>
                            </div>

                        </div>

                    </form>
                    
                </div>
            </div>

        </div>
</html>

        ';
    return $content;
    }
}