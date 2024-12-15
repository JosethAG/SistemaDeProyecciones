<?php
class UsuariosModel {
    private $conn;
    private $table_name = "Usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll($nombre, $correo, $rol) {
        $query = "SELECT id, nombre, correo, rol FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, correo=:correo, contrasenna=:contrasenna, rol=:rol";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":contrasenna", $this->contrasenna);
        $stmt->bindParam(":rol", $this->rol);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre=:nombre, correo=:correo, contrasenna=:contrasenna, rol=:rol WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":contrasenna", $this->contrasenna);
        $stmt->bindParam(":rol", $this->rol);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function validateLogin($email, $password) {
        $query = "SELECT id, nombre, correo, rol FROM " . $this->table_name . " WHERE correo = :correo AND contrasenna = :contrasenna";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":correo", $email);
        $stmt->bindParam(":contrasenna", $password);
        $stmt->execute();
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            $_SESSION["loggedin"] = true;
            $_SESSION["user"] = $user;
            header("Location: index.php");
            exit();
        } else {
            return "Correo o contraseña incorrectos.";
        }
    }
}
?>
