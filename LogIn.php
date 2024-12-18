<?php
session_start();

// Validar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === "admin@admin.com" && $password == "admin") {
        $_SESSION["loggedin"] = true; 
        header("Location: index.php"); 
        exit();
    } else {
        $error_message = "Correo o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="wrapper">
        <div class="form-container">
            <h2 class="text-center mb-4">LogIn</h2>
            <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger"><?= $error_message; ?></div>
            <?php endif; ?>
            <form id="loginForm" method="POST" action="controllers/procesar_login.php">
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo:</label>
                    <input type="email" id="correo" name="correo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="contrasenna" class="form-label">Contraseña:</label>
                    <input type="password" id="contrasenna" name="contrasenna" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-outline-success w-100 mb-3">Iniciar Sesión</button>
                <a href="registro.php" class="btn btn-outline-primary w-100">Registrarse</a>
            </form>
        </div>
    </div>
</body>
</html>
