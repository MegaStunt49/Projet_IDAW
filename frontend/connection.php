<form id="login_form" action="connected.php" method="POST">
    <table>
        <tr>
            <th>Login :</th>
            <td><input type="text" name="login"></td>
        </tr>
        <tr>
            <th>Mot de passe :</th>
            <td><input type="password" name="password"></td>
        </tr>
        <tr>
            <th>Style :</th>
            <td>
                <select name="css">
                    <option value="style1">style1</option>
                    <option value="style2">style2</option>
                </select>
            </td>
        </tr>
        <tr>
            <th></th>
            <td><input type="submit" value="Se connecter..." /></td>
        </tr>
    </table>
</form>
<?php
    if(isset($_GET['error'])) {
        echo ('<p style="color:red;">'.$_GET['error'].'</p>');
    }
?>