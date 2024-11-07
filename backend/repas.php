<?php
require_once("init_pdo.php");

function setHeaders() {
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
}

function getJsonInput() {
    $json = file_get_contents('php://input');
    return json_decode($json, true);
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
    $sql = "SELECT a.libelle, r.date_heure, c.quantite, cc.quantite AS energie
            FROM contient AS c 
            JOIN repas AS r ON r.id_repas=c.id_repas 
            JOIN aliment AS a ON a.id_aliment=c.id_aliment 
            JOIN contient_pour_100g AS cc ON cc.id_aliment=c.id_aliment AND cc.id_caracteristique=6
            WHERE r.login=:login"; 
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

function new_repas($db, $data) {
    if (isset($_SESSION['login'])) {
        $sql = "INSERT INTO repas (login, date_heure) VALUES (:login, :date_heure)"; 
        $exe = $db->prepare($sql);

        $exe->bindParam(':login', $_SESSION['login']);
        $exe->bindParam(':date_heure', $data['dateheure']);

        if ($exe->execute()) {
            $new_repas_id = $db->lastInsertId();
            $sql2 = "INSERT INTO contient (id_repas, id_aliment, quantite) VALUES (:id_repas, :id_aliment, :quantite)";
            $exe2 = $db->prepare($sql2);

            $exe2->bindParam(':id_repas', $new_repas_id );
            $exe2->bindParam(':id_aliment', $data['id_alim']);
            $exe2->bindParam(':quantite', $data['quantite']);

            if ($exe2->execute()) {
                $res = [
                    'id' => $new_repas_id,
                    'login' => $_SESSION['login'],
                    'date_heure' => $data['dateheure'],
                    'id_aliment' => $data['id_alim'],
                    'quantite' => $data['quantite']];
                http_response_code(200);
            } else {
                $res = ["error" => "Failed to create contient."];
                http_response_code(500);
            }
        } else {
            $res = ["error" => "Failed to create repas."];
            http_response_code(500);
        }
    } else {
        $res = ["error" => "Not logged in yet"];
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
            case 'self':
                session_start();
                if (isset($_SESSION['login'])){
                    $result = get_repas_by_login($pdo, $_SESSION['login'] ?? '');
                    setHeaders();
                    echo json_encode($result);
                }
                else {
                    echo json_encode(["error" => "Not logged in"]);
                }
                exit;
            default:
                http_response_code(405);
                exit(json_encode(["error" => "Unknown Request"]));
        }
        exit;
        
    case 'POST':
        $data = getJsonInput();
        if ($data) {
            $result = new_repas($pdo, $data);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid data provided."]);
        }
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