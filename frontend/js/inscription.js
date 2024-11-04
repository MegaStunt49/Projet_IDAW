$(document).ready(function() {
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
});
