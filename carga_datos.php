<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Datos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/carga_datos.js"></script>
</head>
<body>
        <div id="menu">
            <?php include 'menu.php'; ?>
        </div>
    <div class="container">
        <h2 class="text-center mt-4">Gestión de Datos</h2>

        <div class="form-group mt-5">
            <form id="cargarArchivo" enctype="multipart/form-data" method="POST">
                <div class="mb-3">
                    <label for="file" class="form-label">Seleccionar archivo Excel:</label>
                    <input type="file" class="form-control" id="archivo" name="archivo" accept=".xls,.xlsx,.csv" required>
                </div>
                <button type="submit" class="btn btn-primary">Cargar Datos</button>
            </form>
        </div>

        <div class="table-container">
            <table id="dataTable" class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Departamento</th>
                            <th>Cantidad de Clientes</th>
                            <th>Ajuste Manual</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
        </div>
    </div>
</body>
</html>