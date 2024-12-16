<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'sql/db2.php';
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

        foreach ($data as $index => $row) {
            if ($index === 0) continue;

            if (empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4])) {
                continue;
            }

            $id_usuario = 1;
            $fecha = date('Y-m-d', strtotime($row[1]));
            $hora = date('H:i:s', strtotime($row[2]));
            $departamento = $row[3];
            $can_clientes = (int)$row[4];
            $editado = (int)$row[5];

            $sql = "INSERT INTO historicos (id_usuario,fecha, hora, departamento, can_clientes, editado) VALUES ($id_usuario, '$fecha', '$hora', '$departamento', '$can_clientes', '$editado')";
            
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