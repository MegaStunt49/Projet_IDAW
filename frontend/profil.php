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
<div class="main">
    <div class="titre">
        <h1>Profil</h1>
    </div>
    <div class="contenu">
        <div class="login">
            <h2>Login</h2>
        </div>
        <div class="pseudo">
            <h3>Pseudo</h3>
        </div>
        <div class="email">
            <p>email@email.com</p>
        </div>
        <div class="password">
            <p>••••••••</p>
        </div>
        <div class="birthyear">
            <p>Année</p>
        </div>
        <div class="sexe">
            <p>Sexe</p>
        </div>
        <div class="niveauSportif">
            <p>Niveau Sportif</p>
        </div>
        <div class="boutons">
            <button>Edit</button>
        </div>
    </div>
</div>

<?php
    require_once('templates/template_footer.php');
?>