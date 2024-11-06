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
    <script src="js/aliments.js"></script>

    <div id="log-container">
        <p id="log-paragraph"></p>
    </div>
</div>

<?php
echo '<div id="config" data-api-prefix="'. _PREFIX . '"></div>';
require_once("templates/template_footer.php");
?>