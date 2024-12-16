<?php
include 'sql/db2.php';

$sql = "SELECT id, fecha, hora, departamento, can_clientes, editado FROM historicos";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'fecha' => $row['fecha'],
            'hora' => $row['hora'],
            'departamento' => $row['departamento'],
            'can_clientes' => $row['can_clientes'],
            'editado' => $row['editado'] ? 'Sí' : 'No'
        ];
    }
}

header('Content-Type: application/json');
echo json_encode(['data' => $data]);
