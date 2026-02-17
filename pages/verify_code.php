<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificação de Código - Advocacia Bertoldi</title>
  <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
  <link rel="stylesheet" href="../styles/css/login.css">
  <link rel="stylesheet" href="../js/materialize/materialize.min.css">
</head>

<body>

  <div class="auth-card">

    <h1>ADVOCACIA BERTOLDI</h1>
    <p>Digite o código de autenticação enviado para seu e-mail.</p>

    <form method="POST" action="../services/Controller/VerifyCodeController.php">

      <div class="input-group">
        <input 
          name="verification_code" 
          id="verification_code" 
          type="text" 
          maxlength="6" 
          required
        >
        <i class="fa-solid fa-key"></i>
      </div>

      <button class="btn-primary" type="submit" id="verify">
        Verificar
      </button>

      <button 
        type="button" 
        onclick="window.location.href='login.php'" 
        class="btn-secondary">
        Voltar ao login
      </button>

    </form>

  </div>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/materialize/materialize.min.js"></script>
  <script src="../js/swal/sweetalert2.all.min.js"></script>

</body>
</html>
