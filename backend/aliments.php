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

function get_aliments($db, $id) {
    if(isset($id) && $id != '') {
        $sql = "SELECT aliment.id_aliment, aliment.libelle, type_d_aliment.libelle AS type_aliment, aliment.id_type_aliment FROM aliment, type_d_aliment 
        WHERE aliment.id_aliment=:id AND aliment.id_type_aliment = type_d_aliment.id_type_aliment"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':id', $id);
        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch aliment."];
            http_response_code(500);
        }
    } else {
        $sql = "SELECT aliment.id_aliment, aliment.libelle, type_d_aliment.libelle AS type_aliment, aliment.id_type_aliment FROM aliment, type_d_aliment 
        WHERE aliment.id_type_aliment = type_d_aliment.id_type_aliment";

        $exe = $db->query($sql); 
        if ($exe) {
            $res = $exe->fetchAll(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch aliments."];
            http_response_code(500);
        }
    }
    
    return $res;
}

function new_aliment($db, $libelle, $id_type) {
    $sql = "INSERT INTO aliment (id_type_aliment, libelle) VALUES (:id_type, :libelle)"; 
    $exe = $db->prepare($sql);

    $exe->bindParam(':id_type', $id_type);
    $exe->bindParam(':libelle', $libelle);

    if ($exe->execute()) {
        $new_aliment_id = $db->lastInsertId();
        $res = [
            'id' => $new_aliment_id,
            'id_type_aliment' => $id_type,
            'libelle' => $libelle];
        http_response_code(201);
    } else {
        $res = ["error" => "Failed to create aliment."];
        http_response_code(500);
    }
    return $res;
}

function update_aliment($db, $id, $id_type, $libelle) {
    $sql = "UPDATE aliment SET libelle = :libelle, id_type_aliment = :id_type WHERE id_aliment = :id";
    $exe = $db->prepare($sql);

    $exe->bindParam(':id', $id);
    $exe->bindParam(':id_type', $id_type);
    $exe->bindParam(':libelle', $libelle);

    if ($exe->execute()) {
        $rowCount = $exe->rowCount();

        if ($rowCount > 0) {
            $res = [
                'id' => $id,
                'id_type' => $id_type,
                'libelle' => $libelle];
            http_response_code(200);
        } else {
            $res = ['error' => "Aliment not found or no changes made."];
            http_response_code(404);
        }
    } else {
        $res = ["error" => "Failed to update Aliment."];
        http_response_code(500);
    }

    return $res;
}

function delete_aliment($db, $id) {
    $sql = "DELETE FROM aliment WHERE id_aliment = :id"; 
    $exe = $db->prepare($sql);

    $exe->bindParam(':id', $id);

    if ($exe->execute()) {
        if ($exe->rowCount() > 0) {
            $res = [
                "message" => "Aliment deleted successfully.",
                "id" => $id];
            http_response_code(200);
        } else {
            $res = ["error" => "Aliment not found."];
            http_response_code(404);
        }
    } else {
        $res = ["error" => "Failed to delete Aliment."];
        http_response_code(500);
    }

    return $res;
}

switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        $result = get_aliments($pdo, $inputArray[0]);
        setHeaders();
        echo json_encode($result);
        exit;
        
    case 'POST':
        $postData = json_decode(file_get_contents("php://input"), true);
        if (isset($postData['libelle']) && isset($postData['id_type'])) {
            $result = new_aliment($pdo, $postData['libelle'], $postData['id_type']);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid data provided."]);
        }
        exit;

    case 'PUT':
        $putData = json_decode(file_get_contents("php://input"), true);
        if (isset($inputArray[0], $putData['id_type'], $putData['libelle'])) {
            $result = update_aliment($pdo, $inputArray[0], $putData['id_type'], $putData['libelle']);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid data provided."]);
        }
        exit;
    
    case 'DELETE':
        $result = delete_aliment($pdo, $inputArray[0]);
        echo json_encode($result);
        exit;
        
    default:
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
        http_response_code(405);
        exit(json_encode(["error" => "Method Not Allowed"]));
}