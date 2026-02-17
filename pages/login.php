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

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>

    <!-- Materialize -->
    <link rel="stylesheet" href="../js/materialize/materialize.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #2c3e50 0%, #162433 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            padding: 45px 35px;
            border-radius: 14px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.35);
            transition: 0.3s ease;
        }

        .login-card h1 {
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 35px;
            color: #2c3e50;
            letter-spacing: 1px;
        }

        .input-group {
            position: relative;
            margin-bottom: 22px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 45px 14px 14px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 2px rgba(44,62,80,0.2);
            outline: none;
        }

        .toggle-password {
            cursor: pointer;
            color: #888;
        }

        .toggle-password:hover {
            color: #2c3e50;
        }

        .input-group i {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            border-radius: 8px;
            border: none;
            background: #2c3e50;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #1f2d3a;
        }

        .link-action {
            background: none;
            border: none;
            color: #2c3e50;
            font-size: 13px;
            margin-top: 15px;
            cursor: pointer;
            display: block;
            text-align: center;
        }

        .hidden {
            display: none;
        }

        .alert-box {
            width: 100%;
            max-width: 420px;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-size: 14px;
        }

        .alert-danger {
            background: #ffe5e5;
            border: 1px solid #ff9999;
            color: #b30000;
        }

        .alert-warning {
            background: #ff9800;
            color: #fff;
        }

        .dev {
            position: fixed;
            bottom: 15px;
            text-align: center;
            color: #fff;
            font-size: 12px;
        }

        .dev a {
            color: #fff;
            text-decoration: underline;
        }

        @media(max-width: 480px){
            .login-card{
                margin: 0 15px;
                padding: 35px 25px;
            }
        }
    </style>
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

<script>
function toggleForgot() {
    document.getElementById('loginForm').classList.toggle('hidden');
    document.getElementById('forgotForm').classList.toggle('hidden');
}

setTimeout(() => {
    document.getElementById('session-expired')?.remove();
    document.getElementById('mfa-expired')?.remove();
}, 6000);

function togglePassword() {
    const passwordInput = document.getElementById("pass");
    const icon = document.querySelector(".toggle-password");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>
