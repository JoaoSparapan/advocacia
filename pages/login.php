<?php
session_start();
session_unset();
session_destroy();

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
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <link rel="stylesheet" href="../styles/css/login.css">
    <link rel="stylesheet" href="../js/materialize/materialize.min.css">
</head>
<body>

<?php if ($sessionExpired): ?>
    <div id="session-expired" class="alert-box alert-danger">
        ⚠️ Sua sessão expirou por inatividade. Faça login novamente.
    </div>
<?php endif; ?>

<?php if ($mfaExpired): ?>
    <div id="mfa-expired" class="alert-box alert-warning">
        🔒 Sua autenticação de dois fatores expirou. Faça login novamente.
    </div>
<?php endif; ?>

<div class="login-card">

    <h1>ADVOCACIA BERTOLDI</h1>

    <form method="POST" action="../services/Controller/LoginController.php" id="loginForm">
        
        <!-- EMAIL LOGIN -->
        <div class="input-group">
            <input name="email" id="email" type="email" required>
            <i class="fa-solid fa-user"></i>
        </div>

        <!-- SENHA LOGIN -->
        <div class="input-group" id="passwordGroup">
            <input name="senha" id="pass" type="password" required>
            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
        </div>

        <!-- BOTÃO LOGIN -->
        <button class="btn-primary" type="submit" id="log-in">Entrar</button>

        <!-- LINK ESQUECI -->
        <button type="button" class="link-action" onclick="toggleForgot()">
            Esqueci minha senha
        </button>
    </form>

    <!-- FORM RECUPERAÇÃO (apenas email aparece) -->
    <form method="POST" 
          action="../services/Controller/ForgetPassControllerMailer.php"
          id="forgotForm"
          class="hidden">
        <p style="font-size:13px;margin-bottom:20px;color:#555;">
                Informe seu e-mail para enviarmos o código de redefinição.
        </p>

        <div class="input-group">
            <input name="email-forg" id="email-forg" type="email" required>
            <i class="fa-solid fa-envelope"></i>
        </div>

        <button class="btn-primary" type="submit" name="forg">
            Enviar código
        </button>

        <button type="button" class="link-action" onclick="toggleForgot()">
            Voltar ao login
        </button>
    </form>

</div>

<div class="dev">
    Desenvolvido por:
    <a href="mailto:felipeteofilosiqueiracosta@gmail.com">felipeteofilosiqueiracosta@gmail.com</a>
    e
    <a href="mailto:sparapan15@hotmail.com">sparapan15@hotmail.com</a>
</div>

<script src="../js/jquery.min.js"></script>
<script src="../js/materialize/materialize.min.js"></script>
<script src="../js/swal/sweetalert2.all.min.js"></script>
<script src="../js/login.js"></script>

</body>
</html>
