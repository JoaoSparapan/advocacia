<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";

if (AuthController::getUser() == null) {
    if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
        http_response_code(401);
        exit;
    }
    header("Location: /advocacia/pages/login.php");
    exit;
}
