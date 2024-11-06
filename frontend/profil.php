<?php
    session_start();

    if(!isset($_SESSION['login']) && !isset($_SESSION['password'])) {
        header('Location: connection.php');
    }


    require_once('templates/template_header.php');

    $currentPageId = "profil";

    require_once('templates/template_sidemenu.php');
    renderSideMenuToHTML($currentPageId);
?>
<div id="main">
    <div class="titre">
        <h1>Profil</h1>
    </div>
    <div class="contenu Profil">
        <div class="login">
            <h2 id="login">Login</h2>
        </div>
        <div class="pseudo">
            <h3 id="pseudo">Pseudo</h3>
        </div>
        <div class="email">
            <p id="email">email@email.com</p>
        </div>
        <div class="password">
            <p id="password">••••••••</p>
        </div>
        <div class="birthyear">
            <p id="birthyear">Année</p>
        </div>
        <div class="sexe">
            <p id="sexe">Sexe</p>
        </div>
        <div class="niveauSportif">
            <p id="niveauSportif">Niveau Sportif</p>
        </div>
        <div class="boutons">
            <button>Edit</button>
        </div>
    </div>
</div>
<script src="js/profil.js"></script>
<?php
    $login = "";
    if(isset($_SESSION['login'])){
        $login = '/'.$_SESSION['login'];
    }
    echo '<div id="config2" data-login="'. $login . '"></div>';
    require_once('templates/template_footer.php');
?>