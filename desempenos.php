<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desempeños</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div id="menu">
    <?php include 'menu.php'; ?>
</div>
    <div class="container mt-5">
        <h1 class="text-center">Gestión de Desempeños</h1>

        <!-- Sección de filtros -->
        <div class="mb-4">
            <h3>Filtros de Históricos</h3>
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" id="filterDepartment" class="form-control" placeholder="Departamento">
                </div>
                <div class="col-md-4">
                    <input type="date" id="startDate" class="form-control">
                </div>
                <div class="col-md-4">
                    <input type="date" id="endDate" class="form-control">
                </div>
            </div>
        </div>

        <!-- Tabla de históricos -->
        <table id="dataTable" class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAllHistoricos"></th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Departamento</th>
                    <th>Cantidad Clientes</th>
                    <th>Editado</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Búsqueda de proyecciones -->
        <div class="mb-4">
            <h3>Búsqueda en Proyecciones</h3>
            <input type="text" id="searchProjection" class="form-control mb-2" placeholder="Buscar proyección">
        </div>

        <!-- Tabla de proyecciones -->
        <table id="projectionTable" class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAllProyecciones"></th>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Departamento</th>
                    <th>Cantidad Clientes</th>
                    <th>Editado</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Botón para generar análisis -->
        <button id="generateAnalysis" class="btn btn-primary w-100 my-4">Generar Análisis</button>

        <!-- Gráficos -->
        <div class="mt-5">
            <h3 class="text-center">Comparativa entre Históricos y Proyecciones</h3>
            <canvas id="comparisonChart" width="400" height="200"></canvas>
            <h3 class="text-center mt-5">Desviación entre Históricos y Proyecciones</h3>
            <canvas id="deviationChart" width="400" height="200"></canvas>
        </div>
    </div>
    <script>
    $(document).ready(function () {
        // Variables globales para los gráficos
        let comparisonChart = null;
        let deviationChart = null;

        // Obtener datos de la tabla de históricos
        function fetchHistoricos() {
            const department = $('#filterDepartment').val();
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            $.get('controllers/DesempenosController.php', {
                action: 'fetchHistoricos',
                department,
                startDate,
                endDate
            }, function (response) {
                if (Array.isArray(response)) {
                    const rows = response.map(item => `
                        <tr>
                            <td><input type="checkbox" class="historico-checkbox" data-value="${item.can_clientes}"></td>
                            <td>${item.fecha_carga}</td>
                            <td>${item.hora}</td>
                            <td>${item.departamento}</td>
                            <td>${item.can_clientes}</td>
                            <td>${item.editado === 1 ? 'Sí' : 'No'}</td>
                        </tr>
                    `).join('');
                    $('#dataTable tbody').html(rows);
                }
            }, 'json');
        }

        // Obtener datos de la tabla de proyecciones
        function fetchProyecciones() {
            const search = $('#searchProjection').val();

            $.get('controllers/DesempenosController.php', {
                action: 'fetchProyecciones',
                search
            }, function (response) {
                if (Array.isArray(response)) {
                    const rows = response.map(item => `
                        <tr>
                            <td><input type="checkbox" class="proyeccion-checkbox" data-value="${item.can_clientes}"></td>
                            <td>${item.id}</td>
                            <td>${item.nombre_proyeccion}</td>
                            <td>${item.fecha}</td>
                            <td>${item.hora}</td>
                            <td>${item.departamento}</td>
                            <td>${item.can_clientes}</td>
                            <td>${item.editado === 1 ? 'Sí' : 'No'}</td>
                        </tr>
                    `).join('');
                    $('#projectionTable tbody').html(rows);
                }
            }, 'json');
        }

        // Generar los gráficos
        function generateAnalysis() {
            const historicos = $('.historico-checkbox:checked').map(function () {
                return parseInt($(this).data('value'), 10);
            }).get();

            const proyecciones = $('.proyeccion-checkbox:checked').map(function () {
                return parseInt($(this).data('value'), 10);
            }).get();

            if (historicos.length === 0 || proyecciones.length === 0) {
                alert('Debes seleccionar al menos un registro en ambas tablas.');
                return;
            }

            // Calcular las sumas totales
            const totalHistoricos = historicos.reduce((sum, value) => sum + value, 0);
            const totalProyecciones = proyecciones.reduce((sum, value) => sum + value, 0);
            const desviacion = Math.abs(totalProyecciones - totalHistoricos);

            // Destruir gráficos anteriores
            if (comparisonChart) comparisonChart.destroy();
            if (deviationChart) deviationChart.destroy();

            // Gráfico #1: Comparativa total de clientes
            const ctxComparison = document.getElementById('comparisonChart').getContext('2d');
            comparisonChart = new Chart(ctxComparison, {
                type: 'bar',
                data: {
                    labels: ['Históricos', 'Proyecciones'],
                    datasets: [{
                        label: 'Total Clientes',
                        data: [totalHistoricos, totalProyecciones],
                        backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)']
                    }]
                },
                options: { responsive: true }
            });

            // Gráfico #2: Comparativa y desviación
            const ctxDeviation = document.getElementById('deviationChart').getContext('2d');
            deviationChart = new Chart(ctxDeviation, {
                type: 'bar',
                data: {
                    labels: ['Históricos', 'Proyecciones', 'Desviación'],
                    datasets: [{
                        label: 'Comparativa y Desviación',
                        data: [totalHistoricos, totalProyecciones, desviacion],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(75, 192, 192, 0.6)'
                        ]
                    }]
                },
                options: { responsive: true }
            });
        }

        // Eventos
        $('#filterDepartment, #startDate, #endDate').on('change', fetchHistoricos);
        $('#searchProjection').on('keyup', fetchProyecciones);
        $('#generateAnalysis').on('click', generateAnalysis);

        $('#selectAllHistoricos').on('change', function () {
            $('.historico-checkbox').prop('checked', $(this).is(':checked'));
        });

        $('#selectAllProyecciones').on('change', function () {
            $('.proyeccion-checkbox').prop('checked', $(this).is(':checked'));
        });

        // Cargar datos iniciales
        fetchHistoricos();
        fetchProyecciones();
    });
</script>

</body>
</html>
