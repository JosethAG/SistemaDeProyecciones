<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="css/styles.css">

    <style>
        
        .chart-container{

        width: 48%;
        min-width: 48%;
        max-width: 48%;
        height: 300px;
        margin: 10px;
        display: inline-block;
        }

    </style>

</head>

<body>
    <div id="menu">
        <?php include 'menu.php'; ?>
    </div>

    <div class="container mt-2">

        <h2 class="text-center mt-4">Dashboard</h2>
        <br>

        <div class="chart-container">
            <canvas id="projectionBarChart"></canvas>
        </div>

        <div class="chart-container">
            <canvas id="projectionLineChart"></canvas>
        </div>

        <div class="chart-container">
            <canvas id="deviationLineChart"></canvas>
        </div>

        <div class="chart-container">
            <canvas id="demandProjectionChart"></canvas>
        </div>
    </div>

    <script>
        $(document).ready(function () {
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
                }
            });

            const projectionBarChart = new Chart(document.getElementById('projectionBarChart'), {
                type: 'bar',
                data: {
                    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                    datasets: [{
                        label: 'Proyecciones de clientes',
                        data: [50, 90, 75, 140, 120, 160],
                        backgroundColor: '#019DD8',
                        borderColor: '#019DD8',
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

            const projectionLineChart = new Chart(document.getElementById('projectionLineChart'), {
                type: 'line',
                data: {
                    labels: ['2023-01-01', '2023-01-02', '2023-01-03'],
                    datasets: [
                        {
                            label: 'Proyección de Clientes',
                            data: [10, 15, 12],
                            backgroundColor: 'rgba(1, 157, 216, 0.2)',
                            borderColor: '#019DD8',
                            borderWidth: 1
                        },
                        {
                            label: 'Datos Históricos',
                            data: [8, 14, 10],
                            backgroundColor: 'rgba(123, 198, 123, 0.2)',
                            borderColor: '#7BC67B',
                            borderWidth: 1,
                            borderDash: [5, 5]
                        }
                    ]
                },
                options: {
                    scales: {
                        x: { beginAtZero: true },
                        y: { beginAtZero: true }
                    }
                }
            });

            const deviationLineChart = new Chart(document.getElementById('deviationLineChart'), {
                type: 'line',
                data: {
                    labels: ['2023-01-01', '2023-01-02', '2023-01-03'],
                    datasets: [{
                        label: 'Desviación (Histórico - Proyección)',
                        data: [-2, -1, -2],
                        borderColor: 'rgba(255, 159, 64, 1)',
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        fill: true,
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

   
            const demandProjectionChart = new Chart(document.getElementById('demandProjectionChart'), {
                type: 'bar',
                data: {
                    labels: ['Servicio al Cliente (2023-01-01)', 'Cajas (2023-01-02)', 'Crédito (2023-01-03)', 'Servicio al Cliente (2023-01-04)', 'Cajas (2023-01-05)'],
                    datasets: [{
                        label: 'Proyecciones de Demanda por Departamento',
                        data: [15, 30, 20, 40, 25],
                        backgroundColor: '#019DD8',
                        borderColor: '#019DD8',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
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