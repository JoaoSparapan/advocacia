<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificação de Código - Advocacia Bertoldi</title>
  <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>

  <!-- Materialize (mantido por compatibilidade) -->
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

    .auth-card {
      width: 100%;
      max-width: 420px;
      background: #ffffff;
      padding: 45px 35px;
      border-radius: 14px;
      box-shadow: 0 25px 50px rgba(0,0,0,0.35);
      transition: 0.3s ease;
      text-align: center;
    }

    .auth-card h1 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 10px;
      color: #2c3e50;
      letter-spacing: 1px;
    }

    .auth-card p {
      font-size: 13px;
      color: #666;
      margin-bottom: 30px;
    }

    .input-group {
      position: relative;
      margin-bottom: 25px;
    }

    .input-group input {
      width: 100%;
      padding: 14px 45px 14px 14px;
      border-radius: 8px;
      border: 1px solid #ddd;
      font-size: 16px;
      letter-spacing: 6px;
      text-align: center;
      transition: 0.3s;
    }

    .input-group input:focus {
      border-color: #2c3e50;
      box-shadow: 0 0 0 2px rgba(44,62,80,0.2);
      outline: none;
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
      margin-bottom: 12px;
    }

    .btn-primary:hover {
      background: #1f2d3a;
    }

    .btn-secondary {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #2c3e50;
      background: transparent;
      color: #2c3e50;
      font-size: 13px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-secondary:hover {
      background: #f4f6f8;
    }

    @media(max-width: 480px){
      .auth-card{
        margin: 0 15px;
        padding: 35px 25px;
      }
    }
  </style>
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
