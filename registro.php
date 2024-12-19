<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="form-container">
                <div id="successMessage" class="alert alert-success d-none" role="alert">
                    ¡Usuario creado exitosamente!
                </div>
                <h2 class="text-center mb-4">Registro</h2>
                <form id="userForm">
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo:</label>
                        <input type="email" id="correo" name="correo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasenna" class="form-label">Contraseña:</label>
                        <input type="password" id="contrasenna" name="contrasenna" class="form-control" required>
                    </div>
                    <input type="hidden" id="rol" name="rol" class="form-control" value="User">
                    <input type="hidden" id="userId" name="userId" class="form-control" value="1">
                    <button type="submit" class="btn btn-outline-success w-100 mb-3">Registrarse</button>
                    <a href="login.php" class="btn btn-outline-primary w-100">Inicar Sesión</a>
                </form>
            </div>
        </div>

        <script>
            $("#userForm").submit(function (e) {
                e.preventDefault();
                const id = $("#userId").val();
                const nombre = $("#nombre").val();
                const correo = $("#correo").val();
                const rol = $("#rol").val();
                const contrasenna = $("#password").val();

                const action = "create";
                $.post(`controllers/UsuariosController.php?action=${action}`, { id, nombre, correo, rol, contrasenna }, function(response) {
                    $("#successMessage").removeClass("d-none");
                    setTimeout(function() {
                        $("#successMessage").addClass("d-none");
                    }, 3000);
                });

                $("#userForm")[0].reset();
                $("#userId").val("");
            });

        </script>
    </body>
</html>
