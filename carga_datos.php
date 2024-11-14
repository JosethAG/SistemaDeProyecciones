<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
        <div id="menu">
        <?php include 'menu.php'; ?>
        </div>
    <div class="container">
        <h2 class="text-center mt-4">Gestión de Datos</h2>

        <div class="form-group mt-5">
            <label for="fileInput">Cargar archivo de Excel:</label>
            <div class="input-group mb-3">
                <input type="file" class="form-control" id="fileInput" accept=".xlsx, .xls, .csv">
            </div>
            <button id="submitFile" class="btn btn-primary mt-3">Cargar datos</button>
        </div>

        <div class="table-container">
            <table id="dataTable" class="table table-dark table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Fecha Carga</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Departamento</th>
                        <th>Cantidad de Clientes</th>
                        <th>Ajuste</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td contenteditable="true">2023-01-01</td>
                        <td contenteditable="true">2023-01-01</td>
                        <td contenteditable="true">10:00:00</td>
                        <td contenteditable="true">Servicio al Cliente</td>
                        <td contenteditable="true">15</td>
                        <td contenteditable="true">No</td>
                        <td>
                            <button class="btn btn-success btn-sm save-btn">Guardar</button>
                            <button class="btn btn-danger btn-sm delete-btn">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
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
                    "next": " Siguiente",
                    "previous": "Anterior "
                }
            }
        });

        $('.delete-btn').on('click', function() {
            if (confirm('¿Seguro que deseas eliminar esta fila?')) {
                $(this).closest('tr').remove();
            }
        });

        $('.save-btn').on('click', function() {
            alert('Cambios guardados');
        });

        $('#submitFile').on('click', function() {
            const fileInput = $('#fileInput').val();
            if (fileInput) {
                alert('Archivo cargado exitosamente');
            } else {
                alert('Por favor selecciona un archivo');
            }
        });
    });
</script>

</body>
</html>