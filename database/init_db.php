<?php
    require_once('connect_db.php');
    $request = $pdo->prepare(file_get_contents("projet.sql"));
    $request->execute();

    $pdo = null;
?>