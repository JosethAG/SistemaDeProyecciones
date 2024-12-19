<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div id="menu">
        <?php include 'menu.php'; ?>
    </div>
    <div class="container">
        <div class="max-w-9xl mx-auto p-4">
            <div class="row">
                <div class="col-md-6">
                    <h4>Datos del Usuario</h4>
                    <form id="userForm">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Ingrese el nombre">
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" placeholder="Ingrese el correo">
                        </div>
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-select" id="rol">
                                <option selected>Seleccione el rol</option>
                                <option value="Admin">Admin</option>
                                <option value="User">User</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="contrasenna" class="form-label">contraseña</label>
                            <input type="password" class="form-control" id="contrasenna" placeholder="Ingrese la contraseña">
                        </div>
                        <input id="userId" type="text" class="d-none">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-success">Guardar</button>
                            <button type="button" class="btn btn-outline-danger">Cancelar</button>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <h4 class="text-center">Lista de Usuarios</h4>
                    <table class="table table-dark table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="List">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadUsers() {
            $.get("controllers/UsuariosController.php?action=read", function (data) {
                console.log(data); // Verifica la respuesta del servidor
                try {
                    const users = data;
                    let list = "";
                    users.forEach(user => {
                        list += `
                            <tr>
                                <td><strong>${user.id}</strong></td>
                                <td><strong>${user.nombre}</strong></td>
                                <td><strong>${user.correo}</strong></td>
                                <td><strong>${user.rol}</strong></td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-2" onclick="editUser(${user.id}, '${user.nombre}', '${user.correo}', '${user.rol}')">Editar</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">Eliminar</button>
                                </td>
                            </tr>`;
                    });
                    $("#List").html(list);
                } catch (e) {
                    console.error("Error al cargar usuarios: ", e.message);
                }
            }).fail(function (xhr, status, error) {
                console.error("Error al cargar usuarios:", error);
            });
        }

        function editUser(id, nombre, correo, rol) {
            $("#userId").val(id);
            $("#nombre").val(nombre);
            $("#correo").val(correo);
            $("#rol").val(rol);
        }

        function deleteUser(id) {
            $.post("controllers/UsuariosController.php?action=delete", { id: id }, loadUsers);
        }

        $("#userForm").submit(function (e) {
            e.preventDefault();
            const id = $("#userId").val();
            const nombre = $("#nombre").val();
            const correo = $("#correo").val();
            const rol = $("#rol").val();
            const contrasenna = $("#contrasenna").val();

            const action = id ? "update" : "create";
            $.post(`controllers/UsuariosController.php?action=${action}`, { id, nombre, correo, rol, contrasenna}, loadUsers);

            $("#userForm")[0].reset();
            $("#userId").val("");
        });

        loadUsers();
    </script>
    
</body>
</html>