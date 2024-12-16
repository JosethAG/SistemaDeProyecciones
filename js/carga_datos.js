$(document).ready(function () {
    $('#dataTable').DataTable({
        ajax: {
            url: 'get_data.php',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id', visible: false},
            { data: 'fecha', className: 'editable' },
            { data: 'hora', className: 'editable' },
            { data: 'departamento', className: 'editable' },
            { data: 'can_clientes', className: 'editable' },
            { data: 'editado', className: 'editable' },
            { 
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="guardar-btn btn btn-success btn-sm">Guardar</button>
                        <button class="eliminar-btn btn btn-danger btn-sm">Eliminar</button>
                    `;
                }
            }
        ],

        columnDefs: [
            { targets: [5], orderable: false }
        ],

        language: {
            url: 'js/es-MX.json'
        }
    });

    var table = $('#dataTable').DataTable();

    $('#dataTable tbody').on('dblclick', 'td.editable', function () {
        var cell = table.cell(this);
        var value = cell.data();

        $(this).html(`<input type="text" class="edit-input" value="${value}">`);
        $(this).find('input').focus();

        $(this).find('input').on('blur', function () {
            var newValue = $(this).val();
            cell.data(newValue).draw();
        });
    });

    $('#dataTable tbody').on('click', '.guardar-btn', function () {
        var row = table.row($(this).closest('tr')).data();

        $.ajax({
            url: 'actualizar_datos.php',
            method: 'POST',
            data: {
                id: row.id,
                fecha: row.fecha,
                hora: row.hora,
                departamento: row.departamento,
                can_clientes: row.can_clientes,
            },
            success: function (response) {
                alert('Datos actualizados correctamente.');
                table.ajax.reload(null, false);
            },
            error: function () {
                alert('Error al actualizar los datos.');
            }
        });
    });

    $('#dataTable tbody').on('click', '.eliminar-btn', function () {
        var row = table.row($(this).closest('tr'));
        var rowData = row.data();

        if (confirm('¿Estás seguro de eliminar esta fila?')) {
            $.ajax({
                url: 'eliminar_datos.php',
                method: 'POST',
                data: { fecha: rowData.fecha },
                success: function (response) {
                    alert('Fila eliminada correctamente.');
                    table.ajax.reload(null, false);
                },
                error: function () {
                    alert('Error al eliminar la fila.');
                }
            });
        }
    });

    $('#cargarArchivo').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'procesar_datos.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                if (response.success) {
                    alert('Archivo procesado correctamente.');
                    $('#dataTable').DataTable().ajax.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error en la solicitud:", error);
                console.log("Respuesta del servidor:", xhr.responseText);
                alert('Hubo un error al procesar el archivo.');
            }
        });
    });
});
