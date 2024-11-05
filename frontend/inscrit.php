<?php
    require_once("config.php");
    $login = "anonymous";
    $errorText = "";
    $successfullyRegistered = false;

    if (isset($_POST['login']) 
        && isset($_POST['password']) 
        && isset($_POST['pseudo']) 
        && isset($_POST['mail']) 
        && isset($_POST['annee_naissance']) 
        && isset($_POST['niveau']) 
        && isset($_POST['sexe'])) {

        $postData = [
            "login" => $_POST['login'],
            "id_niveau" => $_POST['niveau'],
            "id_sexe" => $_POST['sexe'],
            "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
            "annee_naissance" => $_POST['annee_naissance'],
            "pseudo" => $_POST['pseudo'],
            "email" => $_POST['mail']
        ];

        $url = _PREFIX . "/backend/users.php";
        $apiKey = _APIKEY;
        
        $curl = curl_init($url);
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            'Content-Type: application/json'
        ]);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);

        $decoded_response = json_decode($response);
        if ($decoded_response && $httpCode == 201) {
            $successfullyRegistered = true;
        } else {
            $errorText = "Mot de passe ou Login incorrect : " . $response;
        }
    } else {
        $errorText = "Merci d'utiliser le formulaire de login";
    }

    if (!$successfullyRegistered) {
        header('Location: connection.php?error=' . urlencode($errorText));
    } else {
        header('Location: connection.php?log=Inscription réussie, vous pouvez vous connecter.');
    }
?>
