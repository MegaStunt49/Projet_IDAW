<?php
require_once("init_pdo.php");

function setHeaders() {
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
}

if(isset($_SERVER['PATH_INFO'])) {
    $cleanedString = trim($_SERVER['PATH_INFO'], '/');
    $inputArray = explode('/', $cleanedString);
} else {
    $inputArray = [
        0 => '',
        1 => '',
        2 => ''
    ];
}

function connect_user($db, $login, $pwd) {
    $sql = "SELECT utilisateur.password FROM utilisateur WHERE utilisateur.login = :login"; 
    $exe = $db->prepare($sql);

    $exe->bindParam(':login', $login);
    
    if ($exe->execute()) {
        $res = $exe->fetch(PDO::FETCH_OBJ);
        
        if ($res && password_verify($pwd, $res->password)) { 
            session_start();
            $_SESSION["login"] = $login;
            return true;
        }
    }
    return false;
}

function is_connected($db) {
    session_start();
    return isset($_SESSION['login']);
}

function is_admin($db) {
    session_start();
    if (isset($_SESSION['login'])) {
        $login = $_SESSION['login'];
        $sql = "SELECT utilisateur.est_admin FROM utilisateur WHERE utilisateur.login = :login"; 
        $exe = $db->prepare($sql);

        $exe->bindParam(':login', $login);
        
        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            
            return $res->est_admin;
        }
        return false;
    }
}

switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        $result = get_users($pdo, $inputArray[0]);
        setHeaders();
        echo json_encode($result);
        exit;
        
    default:
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
        http_response_code(405);
        exit(json_encode(["error" => "Method Not Allowed"]));
}