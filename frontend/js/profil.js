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