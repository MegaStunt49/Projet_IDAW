$(document).ready( function () {
    const prefix = $('#config').data('api-prefix');
    const id_aliment = (new URLSearchParams(window.location.search)).get('id');
    console.log(id_aliment);

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
                        value: item.id_caracteristique,
                        text: item.libelle
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

        $.ajax({
            url: `${prefix}/backend/references.php/caracteristique/${selectedValue}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $("#unite").text(`${data.nom_unite}`);
            }
        });

        $.ajax({
            url: `${prefix}/backend/nutriments.php/${id_aliment}/${selectedValue}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.quantite) {
                    $("#quantite-input").val(`${data.quantite}`);
                } else {
                    $("#quantite-input").val('');
                }
            }
        });
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

function update_nutrient(button) {
    const prefix = $('#config').data('api-prefix');
    const id_aliment = (new URLSearchParams(window.location.search)).get('id');
    const selectedValue = $("#caracSelect").val();

    $.ajax({
        url: `${prefix}/backend/nutriments.php/${id_aliment}/${selectedValue}`,
        method: 'PUT',
        dataType: 'json',
        data: JSON.stringify({ "quantite": $("#quantite-input").val() }),
        success: function(data) {
            showLogMessage("Apport modifié avec succès")
        },
        error: function(xhr, status, error) {
            showLogMessage("Aucun changement effectué");
        }
    });
}