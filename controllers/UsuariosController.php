<?php
require "../config/db.php";
require "../models/usuarios.php";

$dbClass = new Database();
$db = $dbClass->getConnection();
$user = new usuarios($db);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        $user->nombre = $_POST['nombre'];
        $user->correo = $_POST['correo'];
        $user->contrasenna = $_POST['contrasenna'];
        $user->rol = $_POST['rol'];
        echo $user->create() ? json_encode(["message" => "Usuario creado exitosamente"]) : json_encode(["error" => "Error al crear usuario"]);
        break;

    case 'read':
        $id = $_GET['id'] ?? null;
        $nombre = $_GET['nombre'] ?? null;
        $correo = $_GET['correo'] ?? null;
        $rol = $_GET['rol'] ?? null;
        $contrasenna = null;

        $stmt = $user->readAll($id, $nombre, $correo, $rol, $contrasenna);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($users);
        break;

    case 'update':
        $user->id = $_POST['id'];
        $user->nombre = $_POST['nombre'];
        $user->correo = $_POST['correo'];
        $user->contrasenna = $_POST['contrasenna'];
        $user->rol = $_POST['rol'];
        echo $user->update() ? json_encode(["message" => "Usuario actualizado exitosamente"]) : json_encode(["error" => "Error al actualizar usuario"]);
        break;

    case 'delete':
        $user->id = $_POST['id'];
        echo $user->delete() ? json_encode(["message" => "Usuario eliminado exitosamente"]) : json_encode(["error" => "Error al eliminar usuario"]);
        break;

    default:
        echo json_encode(["error" => "Acción no válida."]);
}
?>
