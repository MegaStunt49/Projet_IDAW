<?php
    session_start();

    if(!isset($_SESSION['login']) && !isset($_SESSION['password'])) {
        header('Location: connection.php');
    }


    require_once('template_header.php');