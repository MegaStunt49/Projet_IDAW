<?php
    require_once("config.php");
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="icon" href="imgs/oeuf.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
        <title>iMangerMieux</title>
        <?php 
            if (!isset($_COOKIE["style"])){
                $_COOKIE["style"] = "style1";
            }
            echo ('<link rel="stylesheet" href="css/'.$_COOKIE["style"].'.css">'); 
        ?>
    </head>
    <body>