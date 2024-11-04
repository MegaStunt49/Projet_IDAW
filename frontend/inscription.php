<?php
require_once("templates/template_header.php");
require_once("config.php");
?>
<form id="register_form" action="inscrit.php" method="POST">
    <table>
        <tr>
            <th>Login :</th>
            <td><input type="text" name="login" required></td>
        </tr>
        <tr>
            <th>Mot de passe :</th>
            <td><input type="password" name="password" required></td>
        </tr>
        <tr>
            <th>Pseudo :</th>
            <td><input type="text" name="pseudo" required></td>
        </tr>
        <tr>
            <th>Adresse E-mail :</th>
            <td><input type="email" name="mail" required></td>
        </tr>
        <tr>
            <th>Année de naissance :</th>
            <td><input type="number" id="year" name="year" min="1900" max="2100" required></td>
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
<script src="js/inscription.js"></script>
<?php
    if(isset($_GET['error'])) {
        echo ('<p style="color:red;">'.$_GET['error'].'</p>');
    }
?>