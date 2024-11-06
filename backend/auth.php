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
    $sql = "SELECT utilisateur.password, utilisateur.est_admin FROM utilisateur WHERE utilisateur.login = :login"; 
    $exe = $db->prepare($sql);

    $exe->bindParam(':login', $login);
    
    if ($exe->execute()) {
        $res = $exe->fetch(PDO::FETCH_OBJ);
        
        if ($res && password_verify($pwd, $res->password)) { 
            session_start();
            $_SESSION["login"] = $login;
            $_SESSION["est_admin"] = $res->est_admin;
            return true;
        }
    }
    return false;
}

function is_connected() {
    session_start();
    return isset($_SESSION['login']);
}

function disconnection() {
    session_start();
    session_unset();
    session_destroy();
}

function is_admin() {
    session_start();
    if (isset($_SESSION['est_admin'])) {
        return boolval($_SESSION["est_admin"]);
    }
}

setHeaders();

switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        switch($inputArray[0]) {
            case 'is-connected':
                $result = is_connected();
                echo json_encode(["is_connected" => $result]);
                exit;

            case 'is-admin':
                $result = is_admin();
                echo json_encode(["is_admin" => $result]);
                exit;

            default:
                http_response_code(404);
                echo json_encode(["error" => "Unknown Request"]);
                exit;
        }
        exit;
    
    case 'POST':
        if (isset($inputArray[0], $inputArray[1])) {
            $login = $inputArray[0];
            $pwd = $inputArray[1];
            $result = connect_user($pdo, $login, $pwd);
            echo json_encode(["connected" => $result]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Wrong password or login",
            "connected" => false]);
        }
        exit;
        
    case 'DELETE':
        disconnection();
        echo json_encode(["connected" => false]);
        exit;
            
    default:
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
        http_response_code(405);
        exit(json_encode(["error" => "Method Not Allowed"]));
}