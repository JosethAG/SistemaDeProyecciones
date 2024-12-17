<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
include 'sql/db2.php';

require 'vendor/autoload.php';
use Rubix\ML\Regressors\KDNeighborsRegressor;
use Rubix\ML\Transformers\MinMaxNormalizer;
use Rubix\ML\Pipeline;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;

$departamento = $_GET['departamento'];
$nombreProyeccion = $_GET['nombreProyeccion'];

if ($departamento === 'Todos') {
    $sql = "SELECT fecha, hora, departamento, can_clientes FROM historicos";
    $result = $conn->query($sql);
} else {
    $sql = "SELECT fecha, hora, departamento, can_clientes FROM historicos WHERE departamento = '$departamento'";
    $result = $conn->query($sql);
}

$data = [];
$targets = [];
$departments = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fecha = new DateTime($row['fecha']);
        $hora = new DateTime($row['hora']);

        $data[] = [
            $fecha->getTimestamp(),
            $hora->getTimestamp(),
        ];
        $targets[] = (int)$row['can_clientes'];
        $departments[] = $row['departamento'];
    }
}

// var_dump($data);
// var_dump($targets);

$scaler = new MinMaxNormalizer();
$model = new KDNeighborsRegressor(3);

$dataset = new Labeled($data, $targets);

$pipeline = new Pipeline([$scaler], $model);

$pipeline->train($dataset);

$datosEscalados = $scaler->transform($data);

$today = new DateTime();
$datosProyectar = [];

for ($i = 0; $i < 14; $i++) {
    $date = clone $today;
    $date->modify("+$i day");
    $fechaFromateada = $date->format('Y-m-d');

    for ($hour = 7; $hour <= 17; $hour++) {
        if ($departamento === 'Todos') {
            foreach (array_unique($departments) as $dept) {
                $datosProyectar[] = [
                    $date->getTimestamp(),
                    strtotime("$hour:00:00"),
                ];
            }
        } else {
            $datosProyectar[] = [
                $date->getTimestamp(),
                strtotime("$hour:00:00"),
            ];
        }
    }
}

$features = array_map(function ($entry) {
    return array_slice($entry, 0, 2);
}, $datosProyectar);

$datosEscalados = $scaler->transform($features);

$dataset = new Unlabeled($features);

if (!empty($dataset)) {
    $predictions = $model->predict($dataset);
} else {
    echo json_encode(['error' => 'Los datos de entrada están vacíos o no se pudieron procesar correctamente.']);
    exit;
}

$results = [];
foreach ($predictions as $index => $projection) {
    $date = date('Y-m-d', $datosProyectar[$index][0]);
    $hour = date('H:i', $datosProyectar[$index][1]);
    $dept = $departamento === 'Todos' ? 'Todos' : $departamento;
    $id_usuario = 1;

    $sql = "INSERT INTO proyecciones (id_usuario, nombre_proyeccion, fecha, hora, departamento, can_clientes) VALUES ($id_usuario, '$nombreProyeccion', '$date', '$hour', '$dept', $projection)";
    $conn->query($sql);

    $results[] = [
        'fecha' => $date,
        'hora' => $hour,
        'departamento' => $dept,
        'proyeccion' => $projection
    ];
}

echo json_encode(['projection' => $results]);
