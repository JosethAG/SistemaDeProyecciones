<?php
class ReportesModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getProyecciones($filters = []) {
        $query = "SELECT * FROM Proyecciones WHERE 1";

        if (!empty($filters['department'])) {
            $query .= " AND departamento LIKE :department";
        }
        if (!empty($filters['startDate'])) {
            $query .= " AND fecha >= :startDate";
        }
        if (!empty($filters['endDate'])) {
            $query .= " AND fecha <= :endDate";
        }

        $stmt = $this->db->prepare($query);

        if (!empty($filters['department'])) {
            $department = "%{$filters['department']}%";
            $stmt->bindParam(':department', $department);
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
}
?>
