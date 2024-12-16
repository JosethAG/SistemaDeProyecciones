<?php
require_once '../models/ReportesModel.php';
require_once '../config/db.php';

class ReportesController {
    private $model;

    public function __construct($db) {
        $this->model = new ReportesModel($db);
    }

    public function fetchProyecciones() {
        $filters = [
            'department' => $_GET['department'] ?? null,
            'startDate' => $_GET['startDate'] ?? null,
            'endDate' => $_GET['endDate'] ?? null
        ];

        $data = $this->model->getProyecciones($filters);
        echo json_encode($data);
    }
}

// Manejo de peticiones
if (isset($_GET['action'])) {
    $database = new Database();
    $db = $database->getConnection();
    $controller = new ReportesController($db);

    switch ($_GET['action']) {
        case 'fetchProyecciones':
            $controller->fetchProyecciones();
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Acción no encontrada']);
            break;
    }
}
?>
