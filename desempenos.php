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

    <!-- Filtros de Históricos -->
    <div class="mb-4">
    <h3>Filtros de Históricos</h3>
    <div class="row g-2">
        <div class="col-md-4">
            <label for="filterDepartment" class="form-label">Departamento</label>
            <input type="text" id="filterDepartment" class="form-control" placeholder="Departamento">
        </div>
        <div class="col-md-4">
            <label for="startDate" class="form-label">Fecha Inicio</label>
            <input type="date" id="startDate" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="endDate" class="form-label">Fecha Final</label>
            <input type="date" id="endDate" class="form-control">
        </div>
    </div>
</div>


    <!-- Selector de registros para Históricos -->
    <div class="row mb-2">
        <div class="col-md-6">
            <label>Mostrar:</label>
            <select id="recordsPerPageHistoricos" class="form-select w-auto d-inline-block">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select> registros
        </div>
    </div>

    <!-- Tabla de Históricos -->
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
    <nav>
        <ul class="pagination justify-content-center" id="paginationHistoricos"></ul>
    </nav>

    <!-- Selector de registros para Proyecciones -->
    <div class="mb-4">
        <h3>Búsqueda en Proyecciones</h3>
        <input type="text" id="searchProjection" class="form-control mb-2" placeholder="Buscar proyección">
    </div>
    <div class="row mb-2">
        <div class="col-md-6">
            <label>Mostrar:</label>
            <select id="recordsPerPageProyecciones" class="form-select w-auto d-inline-block">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select> registros
        </div>
    </div>

    <!-- Tabla de Proyecciones -->
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
    <nav>
        <ul class="pagination justify-content-center" id="paginationProyecciones"></ul>
    </nav>

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
    let comparisonChart = null;
    let deviationChart = null;
    let historicosData = [];
    let proyeccionesData = [];
    let currentPageHistoricos = 1;
    let currentPageProyecciones = 1;
    let recordsPerPageHistoricos = 5;
    let recordsPerPageProyecciones = 5;

    function renderTable(data, tableBodyId, currentPage, recordsPerPage, paginationId, checkboxClass) {
    const start = (currentPage - 1) * recordsPerPage;
    const end = start + parseInt(recordsPerPage);
    const paginatedData = data.slice(start, end);

    const rows = paginatedData.map(item => `
        <tr>
            <td><input type="checkbox" class="${checkboxClass}" data-value="${item.can_clientes}"></td>
            ${tableBodyId === 'projectionTable tbody' ? `
                <td>${item.id}</td>
                <td>${item.nombre_proyeccion}</td>
            ` : ''}
            <td>${item.fecha_carga || item.fecha}</td>
            <td>${item.hora}</td>
            <td>${item.departamento}</td>
            <td>${item.can_clientes}</td>
            <td>${item.editado === 1 ? 'Sí' : 'No'}</td>
        </tr>
    `).join('');

    $(`#${tableBodyId}`).html(rows);

    // Renderizar paginación
    const totalPages = Math.ceil(data.length / recordsPerPage);
    let paginationHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        paginationHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>`;
    }
    $(`#${paginationId}`).html(paginationHTML);
}

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
            historicosData = response;
            renderTable(historicosData, 'dataTable tbody', currentPageHistoricos, recordsPerPageHistoricos, 'paginationHistoricos', 'historico-checkbox');
        }, 'json');
    }

    function fetchProyecciones() {
        const search = $('#searchProjection').val();

        $.get('controllers/DesempenosController.php', {
            action: 'fetchProyecciones',
            search
        }, function (response) {
            proyeccionesData = response;
            renderTable(proyeccionesData, 'projectionTable tbody', currentPageProyecciones, recordsPerPageProyecciones, 'paginationProyecciones', 'proyeccion-checkbox');
        }, 'json');
    }

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

        const totalHistoricos = historicos.reduce((sum, value) => sum + value, 0);
        const totalProyecciones = proyecciones.reduce((sum, value) => sum + value, 0);
        const desviacion = Math.abs(totalProyecciones - totalHistoricos);

        if (comparisonChart) comparisonChart.destroy();
        if (deviationChart) deviationChart.destroy();

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

    $('#recordsPerPageHistoricos').on('change', function () {
        recordsPerPageHistoricos = parseInt($(this).val());
        currentPageHistoricos = 1;
        renderTable(historicosData, 'dataTable tbody', currentPageHistoricos, recordsPerPageHistoricos, 'paginationHistoricos', 'historico-checkbox');
    });

    $('#paginationHistoricos').on('click', 'a', function (e) {
        e.preventDefault();
        currentPageHistoricos = parseInt($(this).data('page'));
        renderTable(historicosData, 'dataTable tbody', currentPageHistoricos, recordsPerPageHistoricos, 'paginationHistoricos', 'historico-checkbox');
    });

    $('#recordsPerPageProyecciones').on('change', function () {
        recordsPerPageProyecciones = parseInt($(this).val());
        currentPageProyecciones = 1;
        renderTable(proyeccionesData, 'projectionTable tbody', currentPageProyecciones, recordsPerPageProyecciones, 'paginationProyecciones', 'proyeccion-checkbox');
    });

    $('#paginationProyecciones').on('click', 'a', function (e) {
        e.preventDefault();
        currentPageProyecciones = parseInt($(this).data('page'));
        renderTable(proyeccionesData, 'projectionTable tbody', currentPageProyecciones, recordsPerPageProyecciones, 'paginationProyecciones', 'proyeccion-checkbox');
    });

    $('#filterDepartment, #startDate, #endDate').on('change', fetchHistoricos);
    $('#searchProjection').on('keyup', fetchProyecciones);
    $('#generateAnalysis').on('click', generateAnalysis);

    fetchHistoricos();
    fetchProyecciones();
});
</script>
</body>
</html>
