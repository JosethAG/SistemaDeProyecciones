<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="form-container">
                <h2 class="text-center mb-4">Registro</h2>
                <form id="registerForm" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <a href="login.php" class="btn btn-outline-success w-100 mb-3">Registrarse</button>
                    <a href="login.php" class="btn btn-outline-primary w-100">Inicar Sesión</a>
                </form>
            </div>
        </div>
    </body>
</html>
