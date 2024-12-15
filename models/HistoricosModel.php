<?php
class HistoricosModel {
    private $conn;
    private $table_name = "Historicos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll($department, $startDate, $endDate) {
        $query = "SELECT fecha, hora, departamento, can_clientes FROM " . $this->table_name . " WHERE 1=1";

        $conditions = [];
        $params = [];

        if (!empty($department)) {
            $conditions[] = "departamento = :department";
            $params[":department"] = $department;
        }
        if (!empty($startDate)) {
            $conditions[] = "fecha >= :startDate";
            $params[":startDate"] = $startDate;
        }
        if (!empty($endDate)) {
            $conditions[] = "fecha <= :endDate";
            $params[":endDate"] = $endDate;
        }

        if (!empty($conditions)) {
            $query .= " AND " . implode(" AND ", $conditions);
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
