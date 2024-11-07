<?php
    session_start();

    if(!isset($_SESSION['login']) && !isset($_SESSION['password'])) {
        header('Location: connection.php');
    }


    require_once('templates/template_header.php');

    $currentPageId = "journal";

    require_once('templates/template_sidemenu.php');
    renderSideMenuToHTML($currentPageId);
    $login = $_SESSION['login'];
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
                    <th scope="col">Quantité(g)</th>
                    <th scope="col">Apports caloriques(kcal)</th>
                </tr>
            </thead>
            <tbody id="repasTableBody">
            </tbody>
        </table>
        <div class="horizontal-container">
            <div id="add-aliment" class="section" style="flex:1">
                <h2 class="section-title">Ajouter un Aliment</h2>
                <table id="table1" class="display">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Quantité</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="alimentsTableBody">
                    </tbody>
                </table>
            </div>
            <div id="added-aliment" class="section" style="flex:1">
                <h2 class="section-title">Aliment Ajoutés</h2>
                <table id="table2" class="display">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Quantité</th>
                        </tr>
                    </thead>
                    <tbody id="addedAlimentsTableBody">
                    </tbody>
                </table>
            </div>
            <form id="addRepasForm" action="" onsubmit="onFormSubmit();">
                <h2>Repas</h2>
                <div class="form-group row">
                    <label for="date_heure" class="col-sm-2 col-form-label">Date et heure*</label>
                    <div class="col-sm-3">
                        <input type="datetime-local" id="date_heure" name="date_heure" value="2024-11-01T12:30">
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
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script src="js/journal.js"></script>
        <div id="log-container">
            <p id="log-paragraph"></p>
        </div>
    </div>
</div>

<?php
require_once("templates/template_footer.php");
?>