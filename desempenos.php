<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecciones de Demanda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div id="menu">
    <?php include 'menu.php'; ?>
</div>
<div class="container mt-4">
    <h2 class="text-center">Análisis de Desempeño</h2>

    <h4 class="mt-5">Datos Históricos</h4>
    <div class="row g-3 mb-3 mt-2">
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

    <div class="table-responsive">
        <table id="dataTable" class="table table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllCheckbox"></th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Departamento</th>
                    <th>Cantidad de Clientes</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los datos serán cargados dinámicamente -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            const dataTable = $('#dataTable').DataTable({
                language: {
                    "emptyTable": "No hay información disponible",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total de entradas)",
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

            // Función para cargar datos desde el servidor
            function loadHistoricos() {
                const department = $('#filterDepartment').val();
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();

                console.log("Filtros enviados:", { department, startDate, endDate });

                $.ajax({
                    url: './controllers/HistoricosController.php?action=read',
                    method: 'GET',
                    data: { department, startDate, endDate },
                    success: function(response) {
                        console.log("Datos recibidos del servidor:", response);

                        dataTable.clear(); // Limpiar tabla
                        if (response.length > 0) {
                            response.forEach(row => {
                                dataTable.row.add([
                                    '<input type="checkbox" class="data-row-checkbox">',
                                    row.fecha,
                                    row.hora,
                                    row.departamento,
                                    row.can_clientes
                                ]).draw();
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al cargar los datos:", status, error);
                        console.log("Respuesta del servidor:", xhr.responseText);
                    }
                });
            }

            // Recargar datos al cambiar los filtros
            $('#filterDepartment, #startDate, #endDate').on('change', loadHistoricos);

            // Cargar datos al iniciar la página
            loadHistoricos();
        });
    </script>
</body>
</html>
