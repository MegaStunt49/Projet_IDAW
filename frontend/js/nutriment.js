$(document).ready( function () {
    const prefix = $('#config').data('api-prefix');
    const id_aliment = (new URLSearchParams(window.location.search)).get('id');

    //Rempli les sous aliments
    $.ajax({
        url: `${prefix}/backend/subaliments.php/${id_aliment}`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (Array.isArray(data)) {
                data.forEach(item => {
                    $.ajax({
                        url: `${prefix}/backend/aliments.php/${item.id_aliment_enfant}`,
                        method: 'GET',
                        dataType: 'json',
                        success: function(detailData) {
                            $(`<p id="${item.id_aliment_enfant}">${detailData.libelle}
                                <input type="text" value="${item.proportion}"> %
                                <button type="button" class="btn btn-primary" onclick="update_subaliment(this)">
                                    <span class="transition bg-blue"></span>
                                    <span class="gradient"></span>
                                    <span class="label">Update</span>
                                </button>
                                <button type="button" class="btn btn-primary" onclick="remove_subaliment(this)">
                                    <span class="transition bg-red"></span>
                                    <span class="gradient"></span>
                                    <span class="label">Remove</span>
                                </button>
                            </p>`).appendTo($('#aliments-holder'));
                        },
                    });
                });
            }
        },
    });


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

function showLogMessage(message, id) {
    $(`#log-paragraph-${id}`).html(message).fadeIn();
    setTimeout(() => {
        $(`#log-paragraph-${id}`).fadeOut();
    }, 3000); // Hides the message after 3 seconds
}

function add_aliment(button) {
    const prefix = $('#config').data('api-prefix');
    const id_aliment = (new URLSearchParams(window.location.search)).get('id');
    let table = $('#table').DataTable();
    let row = $(button).closest('tr');
    let id_aliment_enfant = table.row(row).data().id_aliment;
    let libelle = table.row(row).data().libelle;

    $.ajax({
        url: `${prefix}/backend/subaliments.php/${id_aliment}/${id_aliment_enfant}`,
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({ "proportion": 0 }),
        success: function(data) {
            showLogMessage("Sous-Aliment modifié avec succès", 2);
            $(`<p id="${id_aliment_enfant}">${libelle}
                <input type="text" value="0"> %
                <button type="button" class="btn btn-primary" onclick="update_subaliment(this)">
                    <span class="transition bg-blue"></span>
                    <span class="gradient"></span>
                    <span class="label">Update</span>
                </button>
                <button type="button" class="btn btn-primary" onclick="remove_subaliment(this)">
                    <span class="transition bg-red"></span>
                    <span class="gradient"></span>
                    <span class="label">Remove</span>
                </button>
            </p>`).appendTo($('#aliments-holder'));
        },
        error: function(xhr, status, error) {
            showLogMessage("Aucun changement effectué", 2);
        }
    });
}

function update_nutrient(button) {
    const prefix = $('#config').data('api-prefix');
    const id_aliment = (new URLSearchParams(window.location.search)).get('id');
    const selectedValue = $("#caracSelect").val();

    $.ajax({
        url: `${prefix}/backend/nutriments.php/${id_aliment}/${selectedValue}`,
        method: 'PUT',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({ "quantite": $("#quantite-input").val() }),
        success: function(data) {
            showLogMessage("Apport modifié avec succès", 1)
        },
        error: function(xhr, status, error) {
            showLogMessage("Aucun changement effectué", 1);
        }
    });
}

function update_subaliment(button) {
    const prefix = $('#config').data('api-prefix');
    const id_aliment = (new URLSearchParams(window.location.search)).get('id');
    const id_aliment_enfant = button.parentNode.id;
    const proportion = button.closest('p').querySelector('input[type="text"]').value;

    $.ajax({
        url: `${prefix}/backend/subaliments.php/${id_aliment}/${id_aliment_enfant}`,
        method: 'PUT',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({ "proportion": proportion }),
        success: function(data) {
            showLogMessage("Sous-Aliment modifié avec succès", 2)
        },
        error: function(xhr, status, error) {
            showLogMessage("Aucun changement effectué", 2);
        }
    });
}

function remove_subaliment(button) {
    const prefix = $('#config').data('api-prefix');
    const id_aliment = (new URLSearchParams(window.location.search)).get('id');
    const id_aliment_enfant = button.parentNode.id;

    $.ajax({
        url: `${prefix}/backend/subaliments.php/${id_aliment}/${id_aliment_enfant}`,
        method: 'DELETE',
        success: function(data) {
            showLogMessage("Sous-Aliment supprimé avec succès", 2);
            button.parentNode.remove();
        },
        error: function(xhr, status, error) {
            showLogMessage("Aucun changement effectué", 2);
        }
    });
}