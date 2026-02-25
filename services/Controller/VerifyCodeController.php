<?php
session_start();
include_once './AuthController.php';
include_once '../Models/Exceptions.php';

$codigo = trim($_POST['verification_code'] ?? '');
$trustDevice = isset($_POST['trust_device']);

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

        if ($trustDevice) {

            $token = bin2hex(random_bytes(32));
            $auth->createTrustedDevice($user_id, $token);

            setcookie(
                "trusted_device",
                $token,
                [
                    'expires' => time() + (30 * 24 * 60 * 60),
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]
            );
        }

        header("Location: ../../");
        exit;
    }
} else {

    header("Refresh: 2, url=../../pages/verify_code.php");
    $exc = new ExceptionAlert("Código inválido ou expirado.", 2000);
    echo $exc->alerts("error", "Erro");
}
?>