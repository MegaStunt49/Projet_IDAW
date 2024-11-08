$(document).ready( function () {
    const prefix = $('#config').data('api-prefix');

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
            const niveauSelect = $('#sexeSelect');
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

    //Rempli informations profil
    $.ajax({
        url: `${prefix}/backend/auth.php/self`,
        method: 'GET',
        dataType: 'json',
        success: function(login_data) {
            $.ajax({
                url: `${prefix}/backend/users.php/${login_data.login}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    document.getElementById("login").textContent = data[0].login;
                    document.getElementById("pseudo").textContent = data[0].pseudo;
                    document.getElementById("email").textContent = data[0].email;
                    document.getElementById("birthyear").textContent = data[0].annee_naissance;
                    document.getElementById("sexe").textContent = data[0].sexelibelle;
                    document.getElementById("niveauSportif").textContent = data[0].sportlibelle;
                    document.getElementById("loginF").textContent = data[0].login;
                    document.getElementById("pseudoF").value = data[0].pseudo;
                    document.getElementById("mail").value = data[0].email;
                    document.getElementById("annee_naissance").value = data[0].annee_naissance;
                    $("#niveauSelect").find("option").filter(function(index) {
                        return data[0].sportlibelle === $(this).text();
                    }).attr("selected", "selected");
                    $("#sexeSelect").find("option").filter(function(index) {
                        return data[0].sexelibelle === $(this).text();
                    }).attr("selected", "selected");
                }
            });
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

    let login = $("#loginF").text();
    let password = $("#passwordF").val();
    let pseudo = $("#pseudoF").val();
    let email = $("#mail").val();
    let annee_naissance = $("#annee_naissance").val();
    let niveauID = $("#niveauSelect").val();
    let sexeID = $("#sexeSelect").val();
    
    if (password == ""){
        showLogMessage('Veuillez renseigner votre mot de passe');
        return null;
    }

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
            location.href = `profil.php`;
        },
        error: function(xhr, status, error) {
            showLogMessage('Erreur: Impossible de mettre à jour l\'utilisateur');
        }
    });
}