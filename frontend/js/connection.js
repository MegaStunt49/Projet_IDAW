$(document).ready(function() {
    const prefix = $('#config').data('api-prefix');

    $(`#connection`).show();
    $(`#to-inscription`).show();
    $(`#to-connection`).hide();
    $(`#inscription`).hide();

    //Rempli le menu déroulant des niveaux
    $.ajax({
        url: `${prefix}/backend/references.php/niveau`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const niveauSelect = $('#niveauSelect');
            if (Array.isArray(data)) {
                data.forEach(item => {
                    $('<option>', {
                        value: item.ID_NIVEAU,
                        text: item.LIBELLE
                    }).appendTo(niveauSelect);
                });
            }
        }
    });

    //Rempli le menu déroulant des sexes
    $.ajax({
        url: `${prefix}/backend/references.php/sexe`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const sexeSelect = $('#sexeSelect');
            if (Array.isArray(data)) {
                data.forEach(item => {
                    $('<option>', {
                        value: item.ID_SEXE,
                        text: item.LIBELLE
                    }).appendTo(sexeSelect);
                });
            }
        }
    });
});

function showLogMessage(message) {
    $('#log-paragraph').html(message).fadeIn();
    setTimeout(() => {
        $('#log-paragraph').fadeOut();
    }, 3000); // Hides the message after 3 seconds
}

function onInscriptionFormSubmit() {
    event.preventDefault();
    const prefix = $('#config').data('api-prefix');

    let login = $("#login").val();
    let password = $("#password").val();
    let pseudo = $("#pseudo").val();
    let email = $("#mail").val();
    let annee_naissance = $("#annee_naissance").val();
    let niveauID = $("#niveauSelect").val();
    let sexeID = $("#sexeSelect").val();
    
    $.ajax({
        url: `${prefix}/backend/users.php`,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            login: login,
            pseudo: pseudo,
            password: password,
            id_niveau: niveauID,
            id_sexe: sexeID,
            annee_naissance: annee_naissance,
            email: email,
        }),
        success: function(response) {
            showLogMessage('Utilisateur créé avec succès');
            
            $.ajax({
                url: `${prefix}/backend/auth.php/${login}/${password}`,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response && response.connected) {
                        showLogMessage('Connection réalisée avec succès');
                        location.href = `${prefix}/frontend/index.php`;
                    } else {
                        showLogMessage("Mot de passe ou Login incorrect");
                    };
                },
                error: function(xhr, status, error) {
                    showLogMessage('Erreur: Login non trouvé');
                },
            });
        },
        error: function(xhr, status, error) {
            showLogMessage('Erreur: Impossible de créer l\'utilisateur');
        }
    });
}


function onConnectionFormSubmit() {
    event.preventDefault();
    const prefix = $('#config').data('api-prefix');

    let login = $("#loginConnection").val();
    let password = $("#passwordConnection").val();
    
    $.ajax({
        url: `${prefix}/backend/auth.php/${login}/${password}`,
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response && response.connected) {
                showLogMessage('Connection réalisée avec succès');
                location.href = `${prefix}/frontend/index.php`;
            } else {
                showLogMessage("Mot de passe ou Login incorrect");
            };
        },
        error: function(xhr, status, error) {
            showLogMessage('Erreur: Login non trouvé');
        },
    });
}

function to_inscription() {
    $(`#to-connection`).show();
    $(`#inscription`).show();
    $(`#to-inscription`).hide();
    $(`#connection`).hide();
}

function to_connection() {
    $(`#connection`).show();
    $(`#to-inscription`).show();
    $(`#to-connection`).hide();
    $(`#inscription`).hide();
}