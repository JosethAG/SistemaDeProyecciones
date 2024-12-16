<?php
class DesempenoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getHistoricos($filters = []) {
        $query = "SELECT fecha_carga, hora, departamento, can_clientes, editado FROM Historicos WHERE 1";

        if (!empty($filters['department'])) {
            $query .= " AND departamento LIKE :department";
        }

        if (!empty($filters['startDate'])) {
            $query .= " AND fecha_carga >= :startDate";
        }

        if (!empty($filters['endDate'])) {
            $query .= " AND fecha_carga <= :endDate";
        }

        $stmt = $this->db->prepare($query);

        if (!empty($filters['department'])) {
            $stmt->bindValue(':department', "%" . $filters['department'] . "%");
        }

        if (!empty($filters['startDate'])) {
            $stmt->bindParam(':startDate', $filters['startDate']);
        }

        if (!empty($filters['endDate'])) {
            $stmt->bindParam(':endDate', $filters['endDate']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProyecciones($search = null) {
        $query = "SELECT id, nombre_proyeccion, fecha, hora, departamento, can_clientes, editado FROM Proyecciones";

        if (!empty($search)) {
            $query .= " WHERE nombre_proyeccion LIKE :search";
        }

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $searchParam = "%" . $search . "%";
            $stmt->bindParam(':search', $searchParam);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
