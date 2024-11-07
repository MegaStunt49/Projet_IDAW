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
    <div id="removable" class="contenu Profil">
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
    <form style="display: none;" id="modifyUserForm" action="" onsubmit="onFormSubmit();">
        <table>
            <tr>
                <th>Login :</th>
                <h2 type="text" name="login" id="login" required></h2>
            </tr>
            <tr>
                <th>Mot de passe :</th>
                <td><input type="password" name="password" id="password" required></td>
            </tr>
            <tr>
                <th>Pseudo :</th>
                <td><input type="text" name="pseudo" id="pseudo" required></td>
            </tr>
            <tr>
                <th>Adresse E-mail :</th>
                <td><input type="email" name="mail" id="mail" required></td>
            </tr>
            <tr>
                <th>Année de naissance :</th>
                <td><input type="number" id="annee_naissance" name="annee_naissance" min="1900" max="2100" required></td>
            </tr>
            <tr>
                <th>Niveau Sportif :</th>
                <td>
                    <select id="niveauSelect" name="niveau" required>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Sexe :</th>
                <td>
                    <select id="sexeSelect" name="sexe" required>
                    </select>
                </td>
            </tr>
            <tr>
                <th></th>
                <td><input type="submit" value="Se connecter..." /></td>
            </tr>
        </table>
    </form>
</div>
<div class="log-container" id="log-container">
    <p class="log-paragraph" id="log-paragraph"></p>
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