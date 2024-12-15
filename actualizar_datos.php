<?php
include 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $departamento = $_POST['departamento'];
    $can_clientes = $_POST['can_clientes'];

    $sql = "UPDATE historicos SET fecha = '$fecha', hora = '$hora', departamento = '$departamento', can_clientes = '$can_clientes', editado = 1";


    if ($conn->query($sql)) {
        echo 'success';
    } else {
        echo 'error';
    }

}
?>
