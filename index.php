<?php
include 'config/db.php';
$database = new Database();
$pdo = $database->getConnection();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="css/styles.css">

    <style>
        .chart-container {
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

    <?php


    $stmtHistoricos = $pdo->query("SELECT fecha, SUM(can_clientes) as can_clientes FROM Historicos GROUP BY fecha");
    $historicos = $stmtHistoricos->fetchAll(PDO::FETCH_ASSOC);

    $stmtProyecciones = $pdo->query("SELECT fecha, SUM(can_clientes) as can_clientes FROM Proyecciones GROUP BY fecha");
    $proyecciones = $stmtProyecciones->fetchAll(PDO::FETCH_ASSOC);

    $stmtDepartamentos = $pdo->query(
        "SELECT departamento, SUM(can_clientes) as can_clientes
        FROM Proyecciones
        GROUP BY departamento"
    );
    $departamentos = $stmtDepartamentos->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <script>
        $(document).ready(function () {


            const projectionBarChart = new Chart(document.getElementById('projectionBarChart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($proyecciones, 'fecha')); ?>,
                    datasets: [{
                        label: 'Proyecciones de clientes',
                        data: <?php echo json_encode(array_column($proyecciones, 'can_clientes')); ?>,
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
                    labels: <?php echo json_encode(array_column($historicos, 'fecha')); ?>,
                    datasets: [
                        {
                            label: 'Proyeccion de Clientes',
                            data: <?php echo json_encode(array_column($proyecciones, 'can_clientes')); ?>,
                            borderColor: '#019DD8',
                            backgroundColor: 'rgba(1, 157, 216, 0.2)',
                            borderWidth: 1
                        },
                        {
                            label: 'Datos Historicos',
                            data: <?php echo json_encode(array_column($historicos, 'can_clientes')); ?>,
                            borderColor: '#7BC67B',
                            backgroundColor: 'rgba(123, 198, 123, 0.2)',
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
                    labels: <?php echo json_encode(array_column($historicos, 'fecha')); ?>,
                    datasets: [{
                        label: 'Desviacion (Historico - Proyeccion)',
                        data: <?php

                        $desviaciones = array_map(function ($historico, $proyeccion) {
                            return isset($historico['can_clientes']) && isset($proyeccion['can_clientes'])
                                ? $proyeccion['can_clientes'] - $historico['can_clientes']
                                : 0;
                        }, $historicos, $proyecciones);
                        echo json_encode($desviaciones);
                        ?>,
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
                    labels: <?php echo json_encode(array_column($departamentos, 'departamento')); ?>,
                    datasets: [{
                        label: 'Proyecciones de Demanda por Departamento',
                        data: <?php echo json_encode(array_column($departamentos, 'can_clientes')); ?>,
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