$(document).ready(function() {
    $('#dataTable').DataTable({
        ajax: {
            url: 'get_data.php',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { 
                data: null, 
                render: function(data, type, row) {
                    return `<input type="checkbox" class="data-row-checkbox" data-id="${row.id}">`;
                }
            },
            { data: 'id', visible: false},
            { data: 'fecha_carga' },
            { data: 'fecha' },
            { data: 'hora' },
            { data: 'departamento' },
            { data: 'can_clientes' },
        ],

        columnDefs: [
            { targets: [6], orderable: false }
        ],

        language: {
            url: 'js/es-MX.json'
        }
    });

    const resultsTable = $('#resultsTable').DataTable({
        columns: [
            { title: 'Fecha' },
            { title: 'Hora' },
            { title: 'Departamento' },
            { title: 'Proyección' }
        ],
        language: { url: 'js/es-MX.json' }
    });

    const ctx = document.getElementById('projectionChart').getContext('2d');
    const projectionChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Proyección',
            data: [],
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                type: 'category'
            },
            y: {
                beginAtZero: true
            }
        }
    }
});

    var table = $('#dataTable').DataTable();

    $('#filterDepartment').on('change', function() {
        var department = $(this).val();
        if (department === 'Todos'){
            department = '';
        }
        table.column(5).search(department).draw();
    });

    $('#startDate').on('change', function() {
        var startDate = $(this).val();
        var endDate = $('#endDate').val();
        
        table.column(2).search(startDate ? '^' + startDate : '', true, false).draw();
    });

    $('#selectAll, #selectAllCheckbox').on('click', function() {
        const allChecked = $('#selectAllCheckbox').prop('checked');
        $('.data-row-checkbox').prop('checked', !allChecked);
        $('#selectAllCheckbox').prop('checked', !allChecked);
    });

    $('#generateProjection').on('click', function () {
        const departamento = $('#filterDepartment').val();
        const nameProyection = $('#nameProyection').val();

        if(nameProyection === ""){
            alert("Por favor coloque un nombre para la proyección a generar.")
            return;
        }

        $.ajax({
            url: 'generar_proyeccion.php',
            method: 'GET',
            data: { departamento: departamento,
                    nombreProyeccion: nameProyection
             },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }

                const labels = response.projection.map(item => `${item.fecha} ${item.hora}`);
                const data = response.projection.map(item => item.proyeccion);

                projectionChart.data.labels = labels;
                projectionChart.data.datasets[0].data = data;
                projectionChart.update();

                resultsTable.clear();
                response.projection.forEach(item => {
                    resultsTable.row.add([item.fecha, item.hora, item.departamento, item.proyeccion]);
                });
                resultsTable.draw();
            },
            error: function (xhr, status, error) {
                console.error('Error al generar la proyección:', error);
                alert('Ocurrió un error al generar la proyección. Intente nuevamente.');
            }
        });
    });
});
