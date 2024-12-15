<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('db.php');
header('Content-Type: application/json');

if(isset($_FILES['archivo'])) {
    $archivo = $_FILES['archivo'];
    $nombreArchivo = $archivo['name'];
    $tipoArchivo = $archivo['type'];
    $rutaTemporal = $archivo['tmp_name'];

    if (in_array($tipoArchivo, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])) {
        require_once 'vendor/autoload.php';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaTemporal);
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $index) {
            if ($index === 0) continue;

            $fecha = $index[0];
            $hora = $index[1];
            $departamento = $index[2];
            $can_clientes = $index[3];
            $editado = isset($index[4]) ? $index[4] : 0;

            $sql = "INSERT INTO historicos (id_usuario,fecha, hora, departamento, can_clientes, editado) VALUES (1, '$fecha', '$hora', '$departamento', '$can_clientes', '$editado')";
            
            if ($conn->query($sql) === TRUE) {
                // si funciono
            } else {
                $response['message'] = "Error al insertar los datos: " . $conn->error;
                echo json_encode($response);
                exit();
            }
        }
        $response['success'] = true;
        $response['message'] = "Archivo cargado correctamente.";

    } else {
        $response['message'] = "El tipo de archivo seleccionado no es permitido.";
    }
} else {
    $response['message'] = "Por favor seleccione un archivo.";
}

echo json_encode($response);
?>