<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificação de Código - Advocacia Bertoldi</title>
  <link rel="stylesheet" href="../styles/css/login.css">
  <link rel="shortcut icon" href="../logotipo.ico" type="image/x-icon">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons&.css" rel="stylesheet" />
  <link rel="stylesheet" href="./styles/fontawesome-free-6.1.1-web/css/all.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="logIn z-depth-1">
    <h1 class="logo">SGPI</h1>
    <div>
      <div class="log active">
        <div class="row">
          <form class="col s12" method="POST" action="../services/Controller/VerifyCodeController.php">
            <div class="row">
              <div class="input-field col s10">
                <i class="fa-solid fa-key prefix"></i>
                <input name="verification_code" id="verification_code" type="text" maxlength="6" class="validate" required>
                <label for="verification_code">Código de verificação</label>
                <span class="helper-text" data-error="Informe um código válido" data-success="Correto!"></span>
              </div>
            </div>

            <div class="row">
              <button class="btn waves-effect waves-light" type="submit" id="verify">Verificar</button>
              <button type="button" onclick="window.location.href='login.php'" class="forgot">Voltar ao login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="../js/materialize/materialize.min.js"></script>
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script type="text/javascript" src="../js/swal/sweetalert2.all.min.js"></script>
</body>
</html>
