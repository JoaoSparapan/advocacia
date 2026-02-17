<?php
// Garante que nenhuma sessão anterior persista
session_start();
session_unset();
session_destroy();

// Detecta se a sessão expirou por inatividade ou MFA
$sessionExpired = isset($_GET['session']) && $_GET['session'] === 'expired';
$mfaExpired = isset($_GET['mfa']) && $_GET['mfa'] === 'expired';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Advocacia Bertoldi</title>
    <link rel="stylesheet" href="../styles/css/login.css">
    <link rel="shortcut icon" href="../logotipo.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons&.css" rel="stylesheet" />
    <link rel="stylesheet" href="./styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <?php if ($sessionExpired): ?>
        <div id="session-expired" style="
            background-color: #ffe5e5;
            border: 1px solid #ff9999;
            color: #b30000;
            text-align: center;
            padding: 12px;
            font-size: 15px;
            font-weight: 500;
            border-radius: 6px;
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        ">
            ⚠️ Sua sessão expirou por inatividade. Faça login novamente.
        </div>
        <script>
            setTimeout(() => document.getElementById('session-expired')?.remove(), 6000);
        </script>
    <?php endif; ?>

    <?php if ($mfaExpired): ?>
        <div id="mfa-expired" style="
            background-color: #ff9800;
            color: #fff;
            text-align: center;
            padding: 12px;
            font-size: 15px;
            font-weight: 500;
            border-radius: 6px;
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        ">
            🔒 Sua autenticação de dois fatores expirou. Faça login novamente.
        </div>
        <script>
            setTimeout(() => document.getElementById('mfa-expired')?.remove(), 6000);
        </script>
    <?php endif; ?>

    <div class="logIn z-depth-1">

        <h1 class="logo"> ADVOCACIA </h1>

        <div>
            <div class="log active">
                <div class="row">
                    <form class="col s12" method="POST" action="../services/Controller/LoginController.php">
                        <div class="row">
                            <div class="input-field col s10">

                                <i class="fa-solid fa-user prefix"></i>
                                <input name="email" id="email" type="email" class="validate">
                                <label for="email">Email</label>
                                <span class="helper-text" data-error="Formato de email incorreto"
                                    data-success="Correto!"></span>
                            </div>
                            <div class="input-field col s10">
                                <i class="fa-solid fa-lock prefix"></i>
                                <input name="senha" id="pass" type="password" class="validate">
                                <label for="pass">Senha</label>
                            </div>
                        </div>

                        <div class="row">
                            <button class="btn waves-effect waves-light" type="submit" id="log-in">Entrar
                            </button>
                            <button type="button" class="forgot">Esqueci minha senha</button>
                        </div>
                    </form>
            
                </div>

            </div>
            <div class="forg">
                <div class="row">
                    <form class="col s12" method="POST" action="../services/Controller/ForgetPassControllerMailer.php">
                        <div class="row">
                            <div class="row">
                                <div class="col s10">
                                    Informe seu e-mail para que possamos enviar um código de alteração da senha
                                </div>
                            </div>
                            <div class="input-field col s10">

                                <i class="fa-solid fa-user prefix"></i>
                                <input name="email-forg" id="email-forg" type="email" class="validate">
                                <label for="email-forg">Email</label>
                                <span class="helper-text" data-error="Formato de email incorreto!"
                                    data-success="Correto!"></span>
                            </div>
                            <div class="row">
                                <button class="btn waves-effect waves-light loading" type="submit" name="forg">Enviar
                                </button>

                            </div>

                        </div>

                    </form>
                    <button class="toggleForm">Logar</button>
                </div>
            </div>

        </div>
    </div>

    <div class="dev extra active">
        <span>Desenvolvido por:</span>
        <p><a href="mailto: felipeteofilosiqueiracosta@gmail.com">felipeteofilosiqueiracosta@gmail.com</a>
            e
            <a href="mailto: sparapan15@hotmail.com">sparapan15@hotmail.com</a>
        </p>
    </div>
    <script type="text/javascript" src="../js/materialize/materialize.min.js"></script>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
        integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="../js/swal/sweetalert2.all.min.js"></script>

    <script type="text/javascript" src="../js/login.js"></script>

    <div id="loading-overlay" style="
        display:none;
        position:fixed;
        top:0; left:0;
        width:100%; height:100%;
        background-color:rgba(255,255,255,0.9);
        z-index:9999;
        justify-content:center;
        align-items:center;
        flex-direction:column;
        font-family:'Segoe UI', Arial, sans-serif;
        color:#333;
    ">
        <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-blue-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p style="margin-top:25px;font-size:17px;font-weight:500;color:#2c3e50;text-align:center;">
            Por favor, aguarde.<br>Estamos validando suas credenciais e enviando o código de verificação para o seu
            e-mail.
        </p>
        <small style="margin-top:10px;color:#777;">Este processo pode levar alguns instantes.</small>
    </div>
</body>

</html>