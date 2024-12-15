<?php
require_once "../config/db.php";
require_once "../models/UsuarioModel.php";

$db = (new Database())->getConnection();
$user = new UsuarioModel($db);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        $user->nombre = $_POST['nombre'];
        $user->correo = $_POST['correo'];
        $user->contrasenna = $_POST['contrasenna'];
        $user->rol = $_POST['rol'];
        echo $user->create() ? "Usuario creado exitosamente" : "Error al crear usuario";
        break;

    case 'read':
        $nombre = $_GET['nombre'] ?? null;
        $correo = $_GET['correo'] ?? null;
        $rol = $_GET['rol'] ?? null;
        $users = $user->readAll($nombre, $correo, $rol);
        echo json_encode($users);
        break;

    case 'update':
        $user->id = $_POST['id'];
        $user->nombre = $_POST['nombre'];
        $user->correo = $_POST['correo'];
        $user->contrasenna = $_POST['contrasenna'];
        $user->rol = $_POST['rol'];
        echo $user->update() ? "Usuario actualizado exitosamente" : "Error al actualizar usuario";
        break;

    case 'delete':
        $user->id = $_POST['id'];
        echo $user->delete() ? "Usuario eliminado exitosamente" : "Error al eliminar usuario";
        break;

    case 'login':
        $email = $_POST['email'];
        $password = $_POST['password'];
        $error_message = $user->validateLogin($email, $password);
        if ($error_message) {
            echo $error_message;
        }
        break;

    default:
        echo "Acción no válida.";
}
?>
