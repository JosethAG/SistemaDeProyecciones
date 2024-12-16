<?php
include 'sql/db2.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id =$_POST['id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $departamento = $_POST['departamento'];
    $can_clientes = $_POST['can_clientes'];

    $sql = "UPDATE historicos SET fecha = '$fecha', hora = '$hora', departamento = '$departamento', can_clientes = '$can_clientes', editado = 1 WHERE id = '$id'";


    if ($conn->query($sql)) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
    }

    echo json_encode($response);
}
?>
