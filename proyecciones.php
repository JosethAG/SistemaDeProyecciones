<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Proyecciones de Demanda</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="css/styles.css">
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
                <option value="">Todos</option>
                <option value="Servicio al Cliente">Servicio al Cliente</option>
                <option value="Cajas">Cajas</option>
                <option value="Crédito">Crédito</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="startDate" class="form-label">Fecha de Inicio:</label>
            <input type="date" id="startDate" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="endDate" class="form-label">Fecha Final:</label>
            <input type="date" id="endDate" class="form-control">
        </div>
    </div>

    <div class="d-flex justify-content-start mt-3">
        <button id="selectAll" class="btn btn-secondary">Seleccionar Todos</button>
    </div>

    <div class="table-responsive mt-4">
        <table id="dataTable" class="table table-dark table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllCheckbox"></th>
                    <th>Fecha Carga</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Departamento</th>
                    <th>Cantidad de Clientes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" class="data-row-checkbox"></td>
                    <td>2023-01-01</td>
                    <td>2023-01-01</td>
                    <td>10:00:00</td>
                    <td>Servicio al Cliente</td>
                    <td>15</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-start mt-4">
        <button id="generateProjection" class="btn btn-primary">Generar Proyección</button>
    </div>

        <div class="chart-container mt-5">
            <canvas id="projectionChart"></canvas>
        </div>

        <div class="table-container mt-4">
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
                    <tr>
                        <td>2023-01-02</td>
                        <td>10:00:00</td>
                        <td>Servicio al Cliente</td>
                        <td>25</td>
                    </tr>
                </tbody>
            </table>
            <button id="downloadResults" class="btn btn-success mt-3">Descargar Resultados</button>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable({
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                        "infoFiltered": "(Filtrado de _MAX_ total de entradas)",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ entradas",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords": "No se encontraron resultados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                    dom: '<"d-flex justify-content-between"lf>tip', // Alinea "Mostrar" y "Buscar"
                });

                $('#selectAll, #selectAllCheckbox').on('click', function() {
                    const allChecked = $('#selectAllCheckbox').prop('checked');
                    $('.data-row-checkbox').prop('checked', !allChecked);
                    $('#selectAllCheckbox').prop('checked', !allChecked);
                });

                const ctx = document.getElementById('projectionChart').getContext('2d');
                const projectionChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['2023-01-01', '2023-01-02', '2023-01-03'],
                        datasets: [{
                            label: 'Proyección de Clientes',
                            data: [10, 15, 12],
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: { beginAtZero: true },
                            y: { beginAtZero: true }
                        }
                    }
                });
            });
        </script>

    </body>
</html>
