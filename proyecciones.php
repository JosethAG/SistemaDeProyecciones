<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Proyecciones de Demanda</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="css/styles.css">
        <script src="js/proyecciones.js"></script>
    </head>
    <body>
        <div id="menu">
            <?php include 'menu.php'; ?>
        </div>
    <div class="container">
    <h2 class="text-center mt-4">Proyecciones de Demanda</h2>

    <div class="row mt-4">
        <div class="col-md-4">
            <label for="filterDepartment" class="form-label">Filtrar por Departamento:</label>
            <select id="filterDepartment" class="form-select">
                <option value="Todos">Todos</option>
                <option value="Servicio al Cliente">Servicio al Cliente</option>
                <option value="Cajas">Cajas</option>
                <option value="Crédito">Crédito</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="startDate" class="form-label">Fecha de Inicio:</label>
            <input type="date" id="startDate" class="form-control">
        </div>
    </div>

    <div class="d-flex justify-content-start mt-3">
        <button id="selectAll" class="btn btn-secondary">Seleccionar Todos</button>
    </div>

    <div class="table-container">
        <table id="dataTable" class="table table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllCheckbox"> Seleccionar</th>
                    <th>Id</th>
                    <th>Fecha Carga</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Departamento</th>
                    <th>Cantidad de Clientes</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <label for="nameProyection" class="form-label">Nombre de la Proyección:</label>
            <input type="text" id="nameProyection"></input>
        </div>
        <div class="col-md-4">
            <button id="generateProjection" class="btn btn-primary">Generar Proyección</button>
        </div>
    </div>
        <div class="chart-container mt-5">
            <canvas id="projectionChart"></canvas>
        </div>

        <div class="table-container">
            <table id="resultsTable" class="table table-dark table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Departamento</th>
                        <th>Proyección de Clientes</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <button id="downloadResults" class="btn btn-success mt-3">Descargar Resultados</button>
        </div>

    </body>
</html>
