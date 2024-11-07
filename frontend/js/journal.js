$(document).ready( function () {
    const prefix = $('#config').data('api-prefix');

    //Rempli le menu déroulant des type d'aliments
    $.ajax({
        url: `${prefix}/backend/aliments.php`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const niveauSelect = $('#alimSelect');
            if (Array.isArray(data)) {
                data.forEach(item => {
                    $('<option>', {
                        value: item.ID_ALIMENT,
                        text: item.LIBELLE
                    }).appendTo(niveauSelect);
                });
            }
        }
    });

    $('#table').DataTable({
        ajax: {
            url: `${prefix}/backend/repas.php/self`,
            dataSrc: ''
        },
        columns: [
            { data: 'libelle', title: 'Aliment' },
            { data: 'date_heure', title: 'Date & Heure' },
            { data: 'quantite', title: 'Quantité' },
            { data: 'energie', title: 'Apports caloriques(kcal)' }
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

    let alim_id = $("#alimSelect").val();
    let alim_libelle = $("#alimSelect option:selected").text();
    let date_heure = $("#date_heure").val();
    let quantite = $("#quantite").val();
    
    $.ajax({
        url: `${prefix}/backend/repas.php`,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            id_alim: alim_id,
            dateheure: date_heure,
            quantite: quantite
        }),
        success: function(response) {
            const parsedData = JSON.parse(response);
            // $('#table').DataTable().row.add({
            //     id_aliment: parsedData.id,
            //     libelle: libelle,
            //     type_aliment: type_libelle
            // }).draw();
            // showLogMessage('Aliment créé avec succès');
            
            $("#date_heure").val('');
            $("#alimSelect").val('');
            $("#quantite").val('');
        },
        error: function(xhr, status, error) {
            showLogMessage('Erreur: Impossible de créer le repas');
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
    let rowData = table.row(row).data();

    row.children('td').eq(1).html(`<input type="text" id="editLogin" value="${rowData.login}">`);
    row.children('td').eq(2).html(`<input type="text" id="editEmail" value="${rowData.email}">`);
    
    row.children('td').eq(3).html(`<button type="button" class="btn btn-success" onclick="confirm_update(this)">Save</button>`);
}

function confirm_update(button) {
    let table = $('#table').DataTable();
    let row = $(button).closest('tr');
    let login = row.find('#editLogin').val();
    let email = row.find('#editEmail').val();
    let rowId = table.row(row).data().id;

    table.row(row).data({
        id: rowId,
        login: login,
        email: email
    }).draw();

    $.ajax({
        url: `http://localhost/TP4/exo5/users.php/${rowId}/${login}/${email}`,
        method: 'PUT',
        success: function(response) {
            showLogMessage('Aliment mis à jour avec succès');
        },
        error: function(xhr, status, error) {
            showLogMessage('Erreur: Impossible de mettre à jour l\'aliment');
        }
    });
}