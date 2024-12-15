<?php
class UsuarioModel {
    private $conn;
    private $table_name = "Usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll($nombre, $correo, $rol) {
        $query = "SELECT id, nombre, correo, rol FROM " . $this->table_name . " WHERE 1=1";

        $conditions = [];
        $params = [];

        if (!empty($nombre)) {
            $conditions[] = "nombre LIKE :nombre";
            $params[":nombre"] = "%" . $nombre . "%";
        }
        if (!empty($correo)) {
            $conditions[] = "correo = :correo";
            $params[":correo"] = $correo;
        }
        if (!empty($rol)) {
            $conditions[] = "rol = :rol";
            $params[":rol"] = $rol;
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
