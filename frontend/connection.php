<?php
    require_once('templates/template_header.php');
    require_once('config.php');
?>
<form id="login_form" action="" method="POST">
    <table>
        <tr>
            <th>Login :</th>
            <td><input type="text" name="login" id="login"></td>
        </tr>
        <tr>
            <th>Mot de passe :</th>
            <td><input type="password" name="password" id="password"></td>
        </tr>
        <tr>
            <th></th>
            <td><input type="submit" value="Se connecter..." /></td>
        </tr>
    </table>
</form>
<script>
    function onFormSubmit() {
        event.preventDefault();
        const prefix = $('#config').data('api-prefix');

        let login = $("#login").val();
        let password = $("#password").val();
        
        $.ajax({
            url: `${prefix}/backend/auth.php/${login}/${password}`,
            method: 'POST',
            success: function(response) {
                let decoded_response = json_decode(response);
                if (decoded_response && decoded_response->connected) {
                    showLogMessage('Connection réalisée avec succès');
                    location.href = `${prefix}/frontend/index.php`;
                } else {
                    showLogMessage("Mot de passe ou Login incorrect");
                };
            },
            error: function(xhr, status, error) {
                showLogMessage('Erreur: Login non trouvé');
            }
        });
    }
</script>
<?php
    if(isset($_GET['error'])) {
        echo ('<p style="color:red;">'.$_GET['error'].'</p>');
    }
    if(isset($_GET['log'])) {
        echo ('<p style="color:green;">'.$_GET['log'].'</p>');
    }
    
    echo '<div id="config" data-api-prefix="'. _PREFIX . '"></div>';
    require_once("templates/template_footer.php");
?>