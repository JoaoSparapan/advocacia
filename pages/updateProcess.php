<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/ProcessController.php';
include_once '../services/Controller/VaraController.php';
include_once '../services/Controller/UserController.php';
if(AuthController::getUser()==null){
    header("location:./login.php");
    exit;
}else if(AuthController::getUser()['idRole']==2){
  header("location:../index.php");
  exit;

}

$c = new VaraController();
$court = $c->getAll();
$proc = new ProcessController();
$procSelected=NULL;
$userController = new UserController();
$u = $userController->getAdminAndColaboradorAll();

if(isset($_GET['id'])){
    if(AuthController::getUser()['idRole']==1){
        $procSelected = $proc->getById($_GET['id']);
        
    }else{
        header("location:./process.php");
        exit;
    }
}else{
    header("location:./process.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../styles/css/sidebar-hide.css">
  <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
  <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
  <link rel="stylesheet" href="../styles/css/providences.css">
  <link rel="stylesheet" href="../styles/css/navbar.css">
  <title>Editar processo</title>
</head>

<body>
  <div class="wrapper d-flex align-items-stretch">
    <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php";
        ?>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5">

      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          Alterar informações do processo: <?= $procSelected['numProcess'] ?>
        </div>
      </nav>
      <div class="content-info-user">

        <form class="col s12" method="POST" action="../services/Controller/UpdateCourt.php">
          <input name="id" style="display: none" type="text" required value="<?=$procSelected['idProcess']?>">
          <div class="row">
            <div class="input-field col s3">

              <i class="fa-solid fa-user prefix"></i>
              <input name="nome" id="name" type="text" class="validate" required
                value="<?=$procSelected['numProcess']?>">
              <label for="name">Nome</label>
              <span class="helper-text" data-success="Correto!"></span>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s3">

              <i class="fa-solid fa-user prefix"></i>
              <input name="client" id="client" type="text" class="validate" required
                value="<?=$procSelected['clientName']?>">
              <label for="client">Cliente</label>
              <span class="helper-text" data-success="Correto!"></span>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s3">
              <i class="fa fa-gavel prefix"></i>
              <select name="court" id="select-vara" style="display: none;">

                <option value="-1" disabled selected>Vara</option>

                <?php
                    for($i=0;$i<sizeof($court);$i++)
                    {
                      $selected='';
                      
                      if($court[$i]['idCourt'] == $procSelected['idCourt']){
                        $selected='selected';
                      }
                        echo "<option
                        ".$selected."
                        value='".$court[$i]['idCourt']."'>".$court[$i]['sigla']."
                        </option>";
                    }
                ?>

              </select>

            </div></div>
            <div class="row">        
            <div class="input-field col s3">
                            <i class="fa fa-id-card prefix"></i>
                            <select name="adv" required id="select-adv">
                                <option value="-1" disabled>Advogado Responsável</option>
                                <?php
            
                                    for($i=0;$i<sizeof($u);$i++)
                                    {
                                        $selected = "selected";

                                        if($procSelected['idUser'] != $u[$i]['idUser']){
                                            $selected = "";
                                        }
                                    $namaUser =$u[$i]['name'];
                                    $idUser = $u[$i]['idUser'];
                                    echo '<option '.$selected.' value="'.$idUser.'">'.$namaUser.'</option>';
                                    
                                    }
                                    
                                    ?>

                            </select>
                        </div>
          </div>
          </div>

          <div class="row">
            <button class="btn waves-effect waves-light" type="submit" name="action" id="reg-user">
              <i class="fa-solid fa-pencil"></i>Alterar
            </button>
            <button style="margin-left: 10px;" class="btn waves-effect waves-light" type="button" id="back"
              onClick="goBack()">
              Cancelar
            </button>
          </div>
        </form><br>



      </div>

    </div>
  </div>

  <script src="../js/jqueryAjax.min.js"></script>
  <script src="../js/popper.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/main.js"></script>
  <script src="../js/materialize/materialize.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
    integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="../js/updateProcess.js"></script>
</body>

</html>