<?php 
session_start(); 
include_once './AuthController.php';
include_once './ProvidenceController.php';
include_once './UserController.php';
include_once '../Models/Exceptions.php';

// Check user permission
if(AuthController::getUser()==null || (AuthController::getUser()['idRole']!=1 && AuthController::getUser()['idRole']!=3)){
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Sem permissão']);
    exit;
}

// Get POST data
$idProvidence = isset($_POST['idProvidence']) ? addslashes($_POST['idProvidence']) : '';
$newUserId = isset($_POST['newUserId']) ? addslashes($_POST['newUserId']) : '';

// Validate input
if(empty($idProvidence) || empty($newUserId)){
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
    exit;
}

// Verify the user exists
$userController = new UserController();
$user = $userController->getById($newUserId);
if(!$user){
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Usuário não encontrado']);
    exit;
}

// Update the providence table with the new responsible user
$provController = new ProvidenceController();
$result = $provController->updateProvidenceUser($idProvidence, $newUserId);

if($result[0] == 'success'){
    echo json_encode([
        'status' => 'success', 
        'message' => $result[1],
        'newUserName' => $user['name'],
        'providenceId' => $idProvidence,
        'newUserId' => $newUserId
    ]);
}else{
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $result[1]]);
}
?>
