<?php
    require_once('init_pdo.php');
    $request = $pdo->prepare(file_get_contents("sql/database.sql"));
    $request->execute();

    $pdo = null;