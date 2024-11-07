$(document).ready( function () {
    const prefix = $('#config').data('api-prefix');
    let trueLogin = $('#config2').data('login');

    //Rempli le menu déroulant des type d'aliments
    $.ajax({
        url: `${prefix}/backend/users.php${trueLogin}`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            document.getElementById("login").textContent = data[0].login;
            document.getElementById("pseudo").textContent = data[0].pseudo;
            document.getElementById("email").textContent = data[0].email;
            document.getElementById("birthyear").textContent = data[0].annee_naissance;
            document.getElementById("sexe").textContent = data[0].sexelibelle;
            document.getElementById("niveauSportif").textContent = data[0].sportlibelle;
        }
    });
});

function Edit(button){
    $("#removable").hide();
    $("#modifyUserForm").show();
}

function showLogMessage(message) {
    $('#log-paragraph').html(message).fadeIn();
    setTimeout(() => {
        $('#log-paragraph').fadeOut();
    }, 3000); // Hides the message after 3 seconds
}

function onFormSubmit() {
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
        method: 'PUT',
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
            showLogMessage('Utilisateur mis à jour avec succès');
            
        },
        error: function(xhr, status, error) {
            showLogMessage('Erreur: Impossible de mettre à jour l\'utilisateur');
        }
    });
}