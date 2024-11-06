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
    <h1><?php echo $_GET['aliment']; ?></h1>
    <div class="horizontal-container">
        <div id="add-sub-aliment">
            <table id="table" class="display">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Type</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="alimentsTableBody">
                </tbody>
            </table>
        </div>
        <div id="update-nutrient">
            <p><span id="carac-name"></span>:</p>
            <div>
                <select id="caracSelect" name="carac" required>
                </select>
                <p><span id="unite"></span></p>
            </div>
            <button type="button" class="btn btn-primary" onclick="delete_entry(this)">
                <span class="transition bg-blue"></span>
                <span class="gradient"></span>
                <span class="label">Update</span>
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="js/nutriment.js"></script>
</div>

<?php
echo '<div id="config" data-api-prefix="'. _PREFIX . '"></div>';
require_once("templates/template_footer.php");
?>