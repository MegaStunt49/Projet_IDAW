<?php
    //session_start();

    //if(!isset($_SESSION['login']) && !isset($_SESSION['password'])) {
    //    header('Location: connection.php');
    //}


    require_once('templates/template_header.php');

    $currentPageId = "index";

    require_once('templates/template_sidemenu.php');
    renderSideMenuToHTML($currentPageId);