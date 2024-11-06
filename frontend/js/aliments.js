$(document).ready( function () {
    const prefix = $('#config').data('api-prefix');

    //Rempli le menu déroulant des type d'aliments
    $.ajax({
        url: `${prefix}/backend/references.php/type-aliment`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const niveauSelect = $('#typeSelect');
            if (Array.isArray(data)) {
                data.forEach(item => {
                    $('<option>', {
                        value: item.ID_TYPE_ALIMENT,
                        text: item.LIBELLE
                    }).appendTo(niveauSelect);
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
                    return `<button type="button" class="btn btn-danger" onclick="delete_entry(this)">
                                <span class="transition bg-red"></span>
                                <span class="gradient"></span>
                                <span class="label">Delete</span>
                            </button>
                            <button type="button" class="btn btn-danger" onclick="update_entry(this)">
                                <span class="transition bg-blue"></span>
                                <span class="gradient"></span>
                                <span class="label">Update</span>
                            </button>`;
                }
            }
        ]
    });
});

function showLogMessage(message) {
    $('#log-paragraph').html(message).fadeIn();
    setTimeout(() => {
        $('#log-paragraph').fadeOut();
    }, 3000); // Hides the message after 3 seconds
}

function onFormSubmit() {
    event.preventDefault();
    const prefix = $('#config').data('api-prefix');

    let libelle = $("#libelle").val();
    let type_id = $("#typeSelect").val();
    let type_libelle = $("#typeSelect option:selected").text();
    
    $.ajax({
        url: `${prefix}/backend/aliments.php`,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            libelle: libelle,
            id_type: type_id
        }),
        success: function(response) {
            const parsedData = JSON.parse(response);
            $('#table').DataTable().row.add({
                id_aliment: parsedData.id,
                libelle: libelle,
                type_aliment: type_libelle
            }).draw();
            showLogMessage('Aliment créé avec succès');
            
            $("#libelle").val('');
            $("#typeSelect").val('');
        },
        error: function(xhr, status, error) {
            showLogMessage('Erreur: Impossible de créer l\'aliment');
        }
    });
}

function delete_entry(button) {
    let table = $('#table').DataTable();
    let row = $(button).closest('tr');
    let rowId = table.row(row).data().id;

    table.row(row).remove().draw();

    $.ajax({
        url: `${prefix}/backend/aliments.php/${rowId}`,
        method: 'DELETE',
        success: function(response) {
            showLogMessage('Aliment supprimé avec succès');
        },
        error: function(xhr, status, error) {
            showLogMessage('Erreur: Impossible de supprimer l\'aliment');
        }
    });
}

function update_entry(button) {
    let table = $('#table').DataTable();
    let row = $(button).closest('tr');
    let rowId = table.row(row).data().id;
    let rowLibelle = table.row(row).data().libelle;
    window.location.href = "nutriment.php?aliment=" + rowLibelle + "&id=" + rowId ;
}