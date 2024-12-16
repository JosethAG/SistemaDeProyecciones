<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/DesempenoModel.php';

class DesempenosController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function fetchHistoricos() {
        $filters = [
            'department' => $_GET['department'] ?? null,
            'startDate' => $_GET['startDate'] ?? null,
            'endDate' => $_GET['endDate'] ?? null
        ];

        $data = $this->model->getHistoricos($filters);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function fetchProyecciones() {
        $search = $_GET['search'] ?? null;

        $data = $this->model->getProyecciones($search);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

if (isset($_GET['action'])) {
    $dbClass = new Database();
    $db = $dbClass->getConnection();
    $model = new DesempenoModel($db);
    $controller = new DesempenosController($model);

    switch ($_GET['action']) {
        case 'fetchHistoricos':
            $controller->fetchHistoricos();
            break;
        case 'fetchProyecciones':
            $controller->fetchProyecciones();
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Action not found']);
            break;
    }
}
