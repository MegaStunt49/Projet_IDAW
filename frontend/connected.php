<?php
    require_once("config.php");
    $login = "anonymous";
    $errorText = "";
    $successfullyLogged = false;
    if(isset($_POST['login']) && isset($_POST['password'])) {
        $tryLogin=$_POST['login'];
        $tryPwd=$_POST['password'];

        $url = _PREFIX . "/backend/auth.php";
        $apiKey = _APIKEY;
        
        $curl = curl_init($url);
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($curl, CURLOPT_POST, true);  // Use POST instead of GET
        $data = json_encode(['login' => $tryLogin, 'password' => $tryPwd]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($curl);
        
        curl_close($curl);
        
        $decoded_response = json_decode($response);
        if ($decoded_response && $decoded_response->connected) {
            $successfullyLogged = true;
        } else {
            $errorText = "Mot de passe ou Login incorrect";
        }
    } 
    else
        $errorText = "Merci d'utiliser le formulaire de login";
    if(!$successfullyLogged) {
        header('Location: connection.php?error='.$errorText);
    } 
    else {
        session_start();
        $_SESSION['login'] = $tryLogin;
        header('Location: index.php');
    }