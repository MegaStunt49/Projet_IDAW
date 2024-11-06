<?php
    session_start();

    if(!isset($_SESSION['login']) && !isset($_SESSION['password'])) {
        header('Location: connection.php');
    }


    require_once('templates/template_header.php');
    require_once("config.php");

    $currentPageId = "journal";

    require_once('templates/template_sidemenu.php');
    renderSideMenuToHTML($currentPageId);
?>
<div id="main">
    <div class="titre">
        <h1>Journal</h1>
    </div>
    <div class="contenu Journal">
        <table id="table" class="display">
            <thead>
                <tr>
                    <th scope="col">Aliment</th>
                    <th scope="col">Date & Heure</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Apports caloriques</th>
                </tr>
            </thead>
            <tbody id="repasTableBody">
            </tbody>
        </table>
        <form id="addRepasForm" action="" onsubmit="onFormSubmit();">
            <div class="form-group row">
                <label for="libelle" class="col-sm-2 col-form-label">Nom de l'aliment*</label>
                <div class="col-sm-3">
                    <select id="alimSelect" name="libelle" required>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="date_heure" class="col-sm-2 col-form-label">Date et heure*</label>
                <div class="col-sm-3">
                    <input type="datetime-local" id="date_heure" name="date_heure" value="2024-11-01T12:30">
                </div>
            </div>
            <div class="form-group row">
                <label for="quantite" class="col-sm-2 col-form-label">Quantité*</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="quantite" required>
                </div>
            </div>
            <div class="form-group row">
                <span class="col-sm-2"></span>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary form-control">
                        <span class="transition bg-blue"></span>
                        <span class="gradient"></span>
                        <span class="label">Create</span>
                    </button>
                </div>
            </div>
        </form>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script>
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
                        url: `${prefix}/backend/repas.php/login/`,
                        dataSrc: ''
                    },
                    columns: [
                        { data: 'libelle', title: 'Aliment' },
                        { data: 'date_heure', title: 'Date & Heure' },
                        { data: 'quantite', title: 'Quantité' },
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

                let alim_id = $("#alimSelect").val();
                let alim_libelle = $("#alimSelect option:selected").text();
                let date_heure = $("#date_heure").val();
                let quantite = $("#quantite").val();
                
                $.ajax({
                    url: `${prefix}/backend/repas.php`,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        libelle: libelle,
                        id_type: type_id
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
        </script>
    </div>
</div>