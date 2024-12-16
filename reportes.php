<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet"> <!-- Mantener estilos -->
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div id="menu">
    <?php include 'menu.php'; ?>
</div>
    <div class="container mt-5">
        <h1 class="text-center">Gestión de Reportes</h1>

<!-- Filtros de búsqueda -->
<div class="mb-4">
    <h3>Filtros de Búsqueda</h3>
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


        <!-- Selector para registros y paginación -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Mostrar:</label>
                <select id="recordsPerPage" class="form-select w-auto d-inline-block">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select> registros
            </div>
        </div>

        <!-- Tabla de reportes -->
        <table id="dataTable" class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
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

        <!-- Paginación -->
        <nav>
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>

        <!-- Gráfico dinámico -->
        <div class="mt-5">
            <h3 class="text-center">Gráfico de Clientes a lo Largo del Tiempo</h3>
            <canvas id="reportChart" width="400" height="200"></canvas>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        let reportChart = null;
        let currentPage = 1;
        let recordsPerPage = 5;
        let allData = [];

        function fetchProyecciones() {
            const department = $('#filterDepartment').val();
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            $.get('controllers/ReportesController.php', {
                action: 'fetchProyecciones',
                department,
                startDate,
                endDate
            }, function (response) {
                allData = response;
                renderTable();
            }, 'json');
        }

        function renderTable() {
            const start = (currentPage - 1) * recordsPerPage;
            const end = start + parseInt(recordsPerPage);
            const paginatedData = allData.slice(start, end);

            const rows = paginatedData.map(item => `
                <tr>
                    <td>
                        <input type="checkbox" class="report-checkbox" 
                               data-value="${item.can_clientes}" 
                               data-label="${item.fecha}" 
                               data-time="${item.hora}">
                    </td>
                    <td>${item.nombre_proyeccion}</td>
                    <td>${item.fecha}</td>
                    <td>${item.hora}</td>
                    <td>${item.departamento}</td>
                    <td>${item.can_clientes}</td>
                    <td>${item.editado == 1 ? 'Sí' : 'No'}</td>
                </tr>
            `).join('');
            $('#dataTable tbody').html(rows);

            renderPagination();
            updateChart();
        }

        function renderPagination() {
            const totalPages = Math.ceil(allData.length / recordsPerPage);
            let paginationHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                paginationHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }

            $('#pagination').html(paginationHTML);
        }

        function updateChart() {
            const labels = [];
            const values = [];

            $('.report-checkbox:checked').each(function () {
                const label = `${$(this).data('label')} ${$(this).data('time')}`;
                labels.push(label);
                values.push(parseInt($(this).data('value')));
            });

            if (reportChart) {
                reportChart.destroy();
            }

            const ctx = document.getElementById('reportChart').getContext('2d');
            reportChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Cantidad de Clientes',
                        data: values,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: { responsive: true }
            });
        }

        $('#filterDepartment, #startDate, #endDate').on('change', fetchProyecciones);
        $('#recordsPerPage').on('change', function () {
            recordsPerPage = parseInt($(this).val());
            currentPage = 1;
            renderTable();
        });
        $('#pagination').on('click', 'a', function (e) {
            e.preventDefault();
            currentPage = parseInt($(this).data('page'));
            renderTable();
        });
        $('#dataTable').on('change', '.report-checkbox', updateChart);
        $('#selectAll').on('change', function () {
            $('.report-checkbox').prop('checked', this.checked);
            updateChart();
        });

        fetchProyecciones();
    });
    </script>
</body>
</html>
