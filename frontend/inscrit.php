<?php
    require_once("config.php");
    $login = "anonymous";
    $errorText = "";
    $successfullyRegistered = false;
    if(isset($_POST['login']) 
        && isset($_POST['password']) 
        && isset($_POST['pseudo']) 
        && isset($_POST['mail']) 
        && isset($_POST['annee_naissance']) 
        && isset($_POST['niveau']) 
        && isset($_POST['sexe'])) {

        $tryLogin=$_POST['login'];
        $tryPwd=$_POST['password'];

        $url = _PREFIX . "/backend/users.php/" 
            . $_POST['login'] . "/" 
            . $_POST['niveau'] . "/" 
            . $_POST['sexe'] . "/" 
            . password_hash($_POST['password'], PASSWORD_DEFAULT) . "/" 
            . $_POST['annee_naissance'] . "/" 
            . $_POST['pseudo'] . "/" 
            . $_POST['mail'];
        
        $curl = curl_init($url);
        $apiKey = _APIKEY;
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            'Content-Type: application/json'
        ]);
        curl_setopt($curl, CURLOPT_POST, true);

        $response = curl_exec($curl);
        
        curl_close($curl);
        
        $decoded_response = json_decode($response);
        if ($decoded_response && !isset($decoded_response->error)) {
            $successfullyRegistered = true;
        } else {
            $errorText = "Mot de passe ou Login incorrect";
        }
    } else
        $errorText = "Merci d'utiliser le formulaire de login";
    if(!$successfullyRegistered) {
        header('Location: connection.php?error='.$errorText);
    } 
    else {
        header('Location: connection.php?log= Inscription réussie, vous pouvez vous connecter.');
    }