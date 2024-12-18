<?php
require_once "../config/db.php";
session_start();

$dbClass = new Database();
$db = $dbClass->getConnection();

if ($db === null) {
    die("La conexión a la base de datos no está configurada correctamente.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"]);
    $contrasenna = trim($_POST["contrasenna"]);

    if (empty($correo) || empty($contrasenna)) {
        $error_message = "Por favor, complete todos los campos.";
    } else {
        try {
            $stmt = $db->prepare("SELECT * FROM usuarios WHERE correo = :correo");
            $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if ($contrasenna === $row["contrasenna"]) {
                    $_SESSION["correo"] = $row["correo"];
                    $_SESSION["rol"] = $row["rol"];
                    header("Location: ../index.php");
                    exit();
                } else {
                    $error_message = "Correo o contraseña incorrectos.";
                }
            } else {
                $error_message = "El usuario no existe.";
            }
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }
} else {
    $error_message = "Acceso no permitido.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error en el inicio de sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger text-center">
            <h4><?= isset($error_message) ? htmlspecialchars($error_message) : "Error desconocido."; ?></h4>
        </div>
        <div class="text-center">
            <a href="../LogIn.php" class="btn btn-outline-primary">Volver al inicio de sesión</a>
        </div>
    </div>
</body>
</html>
