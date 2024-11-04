<?php
    // on simule une base de données
    $users = array(
        // login => password
        'riri' => 'fifi',
        'yoda' => 'maitrejedi' );
    $login = "anonymous";
    $errorText = "";
    $successfullyLogged = false;
    if(isset($_POST['login']) && isset($_POST['password'])) {
        $tryLogin=$_POST['login'];
        $tryPwd=$_POST['password'];
        $successfullyLogged = true;
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
?>