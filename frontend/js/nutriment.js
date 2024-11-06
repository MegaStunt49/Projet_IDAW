$(document).ready( function () {
    const prefix = $('#config').data('api-prefix');

    //Rempli le menu déroulant des caracteristiques
    $.ajax({
        url: `${prefix}/backend/references.php/caracteristique`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const caracSelect = $('#caracSelect');
            if (Array.isArray(data)) {
                data.forEach(item => {
                    $('<option>', {
                        value: item.ID_CARACTERISTIQUE,
                        text: item.LIBELLE
                    }).appendTo(caracSelect);
                });
            }
        }
    });

    $('#table').DataTable({
        ajax: {
            url: `${prefix}/backend/aliments.php`,
            dataSrc: ''
        },
        columns: [
            { data: 'id_aliment', title: 'ID' },
            { data: 'libelle', title: 'Libelle' },
            { data: 'type_aliment', title: 'Type' },
            {
                data: null,
                title: 'Actions',
                render: function (data, type, row) {
                    return `<button type="button" class="btn btn-danger" onclick="add_aliment(this)">
                                <span class="transition bg-blue"></span>
                                <span class="gradient"></span>
                                <span class="label">Add</span>
                            </button>`;
                }
            }
        ]
    });

    $('#caracSelect').change(function() {
        const selectedText = $("#caracSelect option:selected").text();
        const selectedValue = $("#caracSelect").val();
        
        $("#unite").text(`${selectedText}`);
    });
});

function showLogMessage(message) {
    $('#log-paragraph').html(message).fadeIn();
    setTimeout(() => {
        $('#log-paragraph').fadeOut();
    }, 3000); // Hides the message after 3 seconds
}

function add_aliment(button) {
    let table = $('#table').DataTable();
    let row = $(button).closest('tr');
    let rowId = table.row(row).data().id;

    table.row(row).remove().draw();
}