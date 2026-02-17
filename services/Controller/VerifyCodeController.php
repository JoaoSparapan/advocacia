<?php
session_start();
include_once './AuthController.php';
include_once '../Models/Exceptions.php';

$codigo = addslashes($_POST['verification_code'] ?? '');
$auth = new AuthController();

if (!isset($_SESSION['pending_user_id'])) {
    header("Refresh: 2, url=../../pages/login.php");
    $exc = new ExceptionAlert("Sessão expirada. Faça login novamente.", 2000);
    echo $exc->alerts("error", "Erro");
    exit;
}

$user_id = $_SESSION['pending_user_id'];

$isValid = $auth->validateVerificationCode($user_id, $codigo);

if ($isValid) {
    $user = $auth->getUserById($user_id);

    if ($user) {
        AuthController::setUser($user);
        unset($_SESSION['pending_user_id']);
        header("Location: ../../");
        exit;
    } else {
        $exc = new ExceptionAlert("Usuário não encontrado.", 2000);
        echo $exc->alerts("error", "Erro");
        header("Refresh: 2, url=../../pages/login.php");
    }

} else {
    $exc = new ExceptionAlert("Código inválido ou expirado.", 2000);
    echo $exc->alerts("error", "Erro");
    header("Refresh: 2, url=../../pages/verify_code.php");
}
?>
