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

function get_apport($db, $id, $id_carac) {
    if(isset($id) && $id_carac != '') {
        $sql = "SELECT id_aliment, id_caracteristique, quantite FROM contient_pour_100g
        WHERE id_aliment=:id AND id_caracteristique = :id_carac"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':id', $id);
        $exe->bindParam(':id_carac', $id_carac);
        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch apport."];
            http_response_code(500);
        }
    } else {
        $sql = "SELECT id_aliment, id_caracteristique, quantite FROM contient_pour_100g
        WHERE id_aliment=:id"; 
        $exe = $db->prepare($sql);

        $exe->bindParam(':id', $id);
        if ($exe->execute()) {
            $res = $exe->fetchAll(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch aliments."];
            http_response_code(500);
        }
    }
    
    return $res;
}

function update_apport($db, $id, $id_carac, $quantite) {
    $sql = "INSERT INTO contient_pour_100g (id_aliment, id_caracteristique, quantite) VALUES (:id, :id_carac, :quantite)
        ON DUPLICATE KEY UPDATE quantite = VALUES(quantite);";
    $exe = $db->prepare($sql);

    $exe->bindParam(':id', $id);
    $exe->bindParam(':id_carac', $id_carac);
    $exe->bindParam(':quantite', $quantite);

    if ($exe->execute()) {
        $rowCount = $exe->rowCount();

        if ($rowCount > 0) {
            $res = [
                'id' => $id,
                'id_carac' => $id_carac,
                'quantite' => $quantite];
            http_response_code(200);
        } else {
            $res = ['error' => "apport not found or no changes made."];
            http_response_code(404);
        }
    } else {
        $res = ["error" => "Failed to update apport."];
        http_response_code(500);
    }

    return $res;
}

switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        $result = get_apport($pdo, $inputArray[0] ?? '',$inputArray[1] ?? '');
        setHeaders();
        echo json_encode($result);
        exit;

    case 'PUT':
        $putData = json_decode(file_get_contents("php://input"), true);
        if (isset($inputArray[0], $inputArray[1], $putData['quantite'])) {
            $result = update_apport($pdo, $inputArray[0], $inputArray[1], $putData['quantite']);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid data provided."]);
        }
        exit;
        
    default:
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
        http_response_code(405);
        exit(json_encode(["error" => "Method Not Allowed"]));
}