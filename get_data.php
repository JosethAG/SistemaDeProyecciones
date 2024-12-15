<?php
include 'db.php';

$sql = "SELECT fecha, hora, departamento, can_clientes, editado FROM historicos";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'fecha' => $row['fecha'],
            'hora' => $row['hora'],
            'departamento' => $row['departamento'],
            'can_clientes' => $row['can_clientes'],
            'editado' => $row['ajuste_manual'] ? 'Sí' : 'No'
        ];
    }
}

header('Content-Type: application/json');
echo json_encode(['data' => $data]);
