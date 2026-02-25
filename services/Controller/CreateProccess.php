<?php
include_once './ProcessController.php';
include_once './VaraController.php';
include_once '../Models/Exceptions.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Requisição inválida";
    exit;
}

$num_proc = trim($_POST['num-proc'] ?? '');
$adv = isset($_POST['adv']) ? addslashes($_POST['adv']) : 1;
$client = trim($_POST['client'] ?? '');
$vara = '';

$vara_obj = new VaraController();

if ($num_proc == "") {
    echo "Informe o número do processo";
    exit;
}

if ($client == "") {
    echo "Informe o cliente do processo";
    exit;
}

if (isset($_POST['court'])) {

    $vara = addslashes($_POST['court']);

} elseif (isset($_POST['new-vara'])) {

    if ($_POST['new-vara'] == "") {
        echo "Informe a vara";
        exit;
    }

    if ($vara_obj->getVaraBySigla($_POST['new-vara'])) {
        echo "Vara já cadastrada no sistema!";
        exit;
    }

    $vara = addslashes($_POST['new-vara']);

    $result = $vara_obj->insertVara($vara);

    if ($result[0] == "error") {
        echo $result[1];
        exit;
    }

    $result = $vara_obj->getLastVara();
    $vara = $result['idCourt'];

} else {
    echo "Selecione a vara";
    exit;
}

$obj_proc = new ProcessController();
$result = $obj_proc->insertProcess($num_proc, $client, $vara, $adv);

if ($result[0] == "error") {
    echo $result[1];
} else {
    echo "Sucesso ao cadastrar processo!";
}

exit;