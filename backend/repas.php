<?php
require_once("init_pdo.php");

function setHeaders() {
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
}

$inputArray = [
    0 => '',
    1 => '',
    2 => ''
];
if(isset($_SERVER['PATH_INFO'])) {
    $cleanedString = trim($_SERVER['PATH_INFO'], '/');
    $inputArray = explode('/', $cleanedString);
}

function get_repas_by_id($db, $id) {
    if(isset($id) && $id != '') {
        $sql = "SELECT * FROM repas WHERE repas.id_repas=:id"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':id', $id);
        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch repas."];
            http_response_code(500);
        }
    } else {
        $sql = "SELECT * FROM repas"; 

        $exe = $db->query($sql); 
        if ($exe) {
            $res = $exe->fetchAll(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch repas."];
            http_response_code(500);
        }
    }
    
    return $res;
}

function get_repas_by_login($db, $login) {
    $sql = "SELECT * FROM repas WHERE repas.login=:login"; 
    $exe = $db->prepare($sql);

    $exe->bindParam(':login', $login);
    if ($exe->execute()) {
        $res = $exe->fetchAll(PDO::FETCH_OBJ);
        http_response_code(201);
    } else {
        $res = ["error" => "Failed to fetch repas."];
        http_response_code(500);
    }
    
    return $res;
}

function new_repas($db, $login, $date_heure) {
    $sql = "INSERT INTO repas (login, date_heure) VALUES (:login, :date_heure)"; 
    $exe = $db->prepare($sql);

    $exe->bindParam(':login', $login);
    $exe->bindParam(':date_heure', $date_heure);

    if ($exe->execute()) {
        $new_repas_id = $db->lastInsertId();
        $res = [
            'id' => $new_repas_id,
            'login' => $login,
            'date_heure' => $date_heure];
        http_response_code(200);
    } else {
        $res = ["error" => "Failed to create repas."];
        http_response_code(500);
    }
    return $res;
}

function update_repas($db, $id, $login, $date_heure) {
    $sql = "UPDATE repas SET login = :login, date_heure = :date_heure WHERE id_repas = :id";
    $exe = $db->prepare($sql);

    $exe->bindParam(':id', $id);
    $exe->bindParam(':login', $login);
    $exe->bindParam(':date_heure', $date_heure);

    if ($exe->execute()) {
        $rowCount = $exe->rowCount();

        if ($rowCount > 0) {
            $res = [
                'id' => $id,
                'login' => $login,
                'date_heure' => $date_heure];
            http_response_code(200);
        } else {
            $res = ['error' => "repas not found or no changes made."];
            http_response_code(404);
        }
    } else {
        $res = ["error" => "Failed to update repas."];
        http_response_code(500);
    }

    return $res;
}

function delete_repas($db, $id) {
    $sql = "DELETE FROM repas WHERE id_repas = :id"; 
    $exe = $db->prepare($sql);

    $exe->bindParam(':id', $id);

    if ($exe->execute()) {
        if ($exe->rowCount() > 0) {
            $res = [
                "message" => "repas deleted successfully.",
                "id" => $id];
            http_response_code(200);
        } else {
            $res = ["error" => "repas not found."];
            http_response_code(404);
        }
    } else {
        $res = ["error" => "Failed to delete repas."];
        http_response_code(500);
    }

    return $res;
}

switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        switch($inputArray[0]) {
            case 'id':
                $result = get_repas_by_id($pdo, $inputArray[1] ?? '');
                setHeaders();
                echo json_encode($result);
                exit;
            case 'login':
                $result = get_repas_by_login($pdo, $inputArray[1] ?? '');
                setHeaders();
                echo json_encode($result);
                exit;
            default:
                http_response_code(405);
                exit(json_encode(["error" => "Unknown Request"]));
        }
        exit;
        
    case 'POST':
        $result = new_repas($pdo, $inputArray[0] ?? '', $inputArray[1] ?? '');
        echo json_encode($result);
        exit;
        
    case 'PUT':
        $result = update_repas($pdo, $inputArray[0] ?? '', $inputArray[1] ?? '', $inputArray[2] ?? '');
        echo json_encode($result);
        exit;
    
    case 'DELETE':
        $result = delete_repas($pdo, $inputArray[0] ?? '');
        echo json_encode($result);
        exit;
        
    default:
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
        http_response_code(405);
        exit(json_encode(["error" => "Method Not Allowed"]));
}