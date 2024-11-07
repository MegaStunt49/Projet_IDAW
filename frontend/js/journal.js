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
            { data: 'quantite', title: 'Quantité(g)' },
            { data: 'energie', title: 'Apports caloriques(kcal)' }
        ]
    });

    $('#table1').DataTable({
        ajax: {
            url: `${prefix}/backend/aliments.php`,
            dataSrc: ''
        },
        columns: [
            { data: 'id_aliment', title: 'ID' },
            { data: 'libelle', title: 'Nom' },
            {
                data: null,
                title: 'Quantité(g)',
                render: function (data, type, row) {
                    return `<input type="text" class="quantite" id="quantiteInput">`;
                }
            },
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
    $('#table2').DataTable();
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

    let alim_id = $("#table2").DataTable().column(0).data().toArray();
    let alim_libelle = $("#table2").DataTable().column(1).data().toArray();
    let quantite = $("#table2").DataTable().column(2).data().toArray();
    let date_heure = $("#date_heure").val();
    date_heure = date_heure.replace("T", " ");
    showLogMessage(alim_id[0]);
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
            showLogMessage('Repas créé avec succès');
            
            $("#date_heure").val('2024-11-01T12:30');
        },
        error: function(xhr, status, error) {
            showLogMessage('Erreur: Impossible de créer le repas');
        }
    });
}

function add_aliment(button) {
    let table1 = $('#table1').DataTable();
    let table = $('#table2').DataTable();
    let row = $(button).closest('tr');
    let id_aliment = table1.row(row).data().id_aliment;
    let libelle = table1.row(row).data().libelle;
    let quantite = row.find('#quantiteInput').val();

    table.row.add([id_aliment,libelle,quantite]).draw(false);
    row.find('#quantiteInput').val("");
}