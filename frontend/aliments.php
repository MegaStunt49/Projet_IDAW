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
    <p id="log-paragraph"></p>
    <table id="table" class="display">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Login</th>
                <th scope="col">Email</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody id="studentsTableBody">
        </tbody>
    </table>
    <form id="addStudentForm" action="" onsubmit="onFormSubmit();">
        <div class="form-group row">
            <label for="inputLogin" class="col-sm-2 col-form-label">Login*</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="inputLogin" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="inputMail" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="inputMail">
            </div>
        </div>
        <div class="form-group row">
            <span class="col-sm-2"></span>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary form-control">Create</button>
            </div>
        </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script>
        $(document).ready( function () {

            const prefix = $('#config').data('api-prefix');

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
                            return `<button type="button" class="btn btn-danger" onclick="delete_entry(this)">Delete</button>
                                    <button type="button" class="btn btn-danger" onclick="update_entry(this)">Update</button>`;
                        }
                    }
                ]
            });
        });

        function onFormSubmit() {
            event.preventDefault();
            let login = $("#inputLogin").val();
            let mail = $("#inputMail").val();
            $.ajax({
                url: `${prefix}/backend/aliments.php/${login}/${mail}`,
                method: 'POST',
                success: function(response) {
                    const parsedData = JSON.parse(response);
                    $('#table').DataTable().row.add({
                        id: parsedData.id,
                        login: login,
                        email: mail
                    }).draw();
                },
                error: function(xhr, status, error) {
                    $('#log-paragraph').html('Erreur: Impossible de créer l\'aliment');
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
                    $('#log-paragraph').html('Aliment supprimer avec succès');
                },
                error: function(xhr, status, error) {
                    $('#log-paragraph').html('Erreur: Impossible de supprimer l\'aliment');
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
                    $('#log-paragraph').html('User updated successfully');
                },
                error: function(xhr, status, error) {
                    $('#log-paragraph').html('Error: Could not update user');
                }
            });
        }
    </script>
</div>

<?php
echo '<div id="config" data-api-prefix="'. _PREFIX . '"></div>';
require_once("templates/template_footer.php");
?>