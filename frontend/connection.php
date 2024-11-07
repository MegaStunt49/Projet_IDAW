<?php
    require_once('templates/template_header.php');
    require_once('config.php');
?>
<h1><img src="imgs/oeuf.png" alt="Logo de iMangerMieux"> i-MangerMieux</h1>
<div class="horizontal-container">
    <div style="flex:1"></div>
    <div class="section vertical-container" style="flex:1">
        <div id="connection">
            <h2 class="section-title">Connexion</h2>
            <form id="login_form" action="" onsubmit="onConnectionFormSubmit();">
                <table>
                    <tr>
                        <th>Login :</th>
                        <td><input type="text" name="loginConnection" id="loginConnection"></td>
                    </tr>
                    <tr>
                        <th>Mot de passe :</th>
                        <td><input type="password" name="passwordConnection" id="passwordConnection"></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <button type="submit" class="btn">
                                <span class="transition bg-blue"></span>
                                <span class="gradient"></span>
                                <span class="label">Se connecter</span>
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="inscription">
            <h2 class="section-title">Inscription</h2>
            <form id="register_form" action="" onsubmit="onInscriptionFormSubmit();">
                <table>
                    <tr>
                        <th>Login :</th>
                        <td><input type="text" name="login" id="login" required></td>
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
                        <td>
                            <button type="submit" class="btn">
                                <span class="transition bg-blue"></span>
                                <span class="gradient"></span>
                                <span class="label">S'inscrire</span>
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="horizontal-container">
            <button id="to-inscription" type="button" class="btn" onclick="to_inscription();" style="margin-left: auto">
                <span class="transition bg-blue"></span>
                <span class="gradient"></span>
                <span class="label">Inscription</span>
            </button>
            <button id="to-connection" type="button" class="btn" onclick="to_connection();">
                <span class="transition bg-blue"></span>
                <span class="gradient"></span>
                <span class="label">Connexion</span>
            </button>
        </div>
    </div>
    <div style="flex:1"></div>
</div>
<div id="log-container">
    <p id="log-paragraph"></p>
</div>
<script src="js/connection.js"></script>
<?php
    if(isset($_GET['error'])) {
        echo ('<p style="color:red;">'.$_GET['error'].'</p>');
    }
    if(isset($_GET['log'])) {
        echo ('<p style="color:green;">'.$_GET['log'].'</p>');
    }
    
    require_once("templates/template_footer.php");
?>