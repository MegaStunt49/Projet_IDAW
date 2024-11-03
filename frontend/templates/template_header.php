<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="icon" href="imgs/place_holder_icon.png">
        <title>iMangerMieux</title>
        <?php 
            if (!isset($_COOKIE["style"])){
                $_COOKIE["style"] = "style1";
            }
            echo ('<link rel="stylesheet" href="css/'.$_COOKIE["style"].'.css">'); 
        ?>
    </head>
    <body>