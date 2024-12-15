<?php
require_once "../config/db.php";
require_once "../models/HistoricosModel.php";

header('Content-Type: application/json'); // Forzar salida JSON

$db = (new Database())->getConnection();
$historicosModel = new HistoricosModel($db);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'read':
        $department = $_GET['department'] ?? null;
        $startDate = $_GET['startDate'] ?? null;
        $endDate = $_GET['endDate'] ?? null;

        $historicos = $historicosModel->readAll($department, $startDate, $endDate);

        echo json_encode($historicos); // Devolver resultados como JSON
        break;

    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}
?>
