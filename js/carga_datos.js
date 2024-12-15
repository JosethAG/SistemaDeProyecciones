$(document).ready(function () {
    // Inicializar DataTable con AJAX
    $('#dataTable').DataTable({
        ajax: {
            url: 'get_data.php',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: 'fecha' },
            { data: 'hora' },
            { data: 'departamento' },
            { data: 'can_clientes' },
            { data: 'ajuste_manual' }
        ],
        language: {
            url: 'js/es-MX.json'
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
