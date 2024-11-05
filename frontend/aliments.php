<?php
    session_start();

    if(!isset($_SESSION['login']) && !isset($_SESSION['password'])) {
        header('Location: connection.php');
    }


    require_once('templates/template_header.php');
    require_once("config.php");

    $currentPageId = "aliments";

    require_once('templates/template_sidemenu.php');
    renderSideMenuToHTML($currentPageId);
?>
<div id="main">
    <table id="table" class="display">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Login</th>
                <th scope="col">Email</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody id="alimentsTableBody">
        </tbody>
    </table>
    <form id="addAlimentForm" action="" onsubmit="onFormSubmit();">
        <div class="form-group row">
            <label for="libelle" class="col-sm-2 col-form-label">Nom de l'aliment*</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="libelle" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 col-form-label">Type d'aliment*</label>
            <div class="col-sm-3">
                <select id="typeSelect" name="type" required>
                </select>
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
                url: `${prefix}/backend/references.php/type-aliment`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const niveauSelect = $('#typeSelect');
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            $('<option>', {
                                value: item.ID_TYPE_ALIMENT,
                                text: item.LIBELLE
                            }).appendTo(niveauSelect);
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

            let libelle = $("#libelle").val();
            let type_id = $("#typeSelect").val();
            let type_libelle = $("#typeSelect option:selected").text();
            
            $.ajax({
                url: `${prefix}/backend/aliments.php`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    libelle: libelle,
                    id_type: type_id
                }),
                success: function(response) {
                    const parsedData = JSON.parse(response);
                    $('#table').DataTable().row.add({
                        id_aliment: parsedData.id,
                        libelle: libelle,
                        type_aliment: type_libelle
                    }).draw();
                    showLogMessage('Aliment créé avec succès');
                    
                    $("#libelle").val('');
                    $("#typeSelect").val('');
                },
                error: function(xhr, status, error) {
                    showLogMessage('Erreur: Impossible de créer l\'aliment');
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

    <div id="log-container">
        <p id="log-paragraph"></p>
    </div>
</div>

<?php
echo '<div id="config" data-api-prefix="'. _PREFIX . '"></div>';
require_once("templates/template_footer.php");
?>