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
    <div class="horizontal-container">
        <div style="flex:1"></div>
        <div class="section" style="flex:1">
            <div id="removable">
                <div class="login">
                    <h2 id="login" class="section-title">Login</h2>
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
                    <button type="button" class="btn" onclick="Edit(this)">
                        <span class="transition bg-blue"></span>
                        <span class="gradient"></span>
                        <span class="label">Edit</span>
                    </button>
                </div>
            </div>
            <form style="display: none;" id="modifyUserForm" action="" onsubmit="onFormSubmit();">
                <h2 type="text" name="loginF" id="loginF" class="section-title" required></h2>
                <table>
                    <tr>
                        <th>Mot de passe :</th>
                        <td><input type="password" name="passwordF" id="passwordF" required></td>
                    </tr>
                    <tr>
                        <th>Pseudo :</th>
                        <td><input type="text" name="pseudoF" id="pseudoF" required></td>
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
                </table>
                <button type="submit" class="btn">
                    <span class="transition bg-blue"></span>
                    <span class="gradient"></span>
                    <span class="label">Update</span>
                </button>
                <button type="button" class="cancel btn" onclick="location.reload();">
                    <span class="transition bg-red"></span>
                    <span class="gradient"></span>
                    <span class="label">Cancel</span>
                </button>
            </form>
        </div>
        <div style="flex:1"></div>
    </div>
</div>
<div class="log-container" id="log-container">
    <p class="log-paragraph" id="log-paragraph"></p>
</div>
<script src="js/profil.js"></script>
<?php
    require_once('templates/template_footer.php');
?>