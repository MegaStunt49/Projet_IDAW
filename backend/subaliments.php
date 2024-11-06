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

function get_subaliments($db, $id, $id_enfant) {
    if(isset($id) && $id_enfant != '') {
        $sql = "SELECT id_aliment, id_aliment_enfant, proportion FROM est_compose_de
        WHERE id_aliment=:id AND id_aliment_enfant = :id_enfant"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':id', $id);
        $exe->bindParam(':id_enfant', $id_enfant);
        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch aliment."];
            http_response_code(500);
        }
    } else {
        $sql = "SELECT id_aliment, id_aliment_enfant, proportion FROM est_compose_de
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

function new_subaliment($db, $id, $id_enfant, $proportion) {
    $sql = "INSERT INTO est_compose_de (id_aliment, id_aliment_enfant, proportion) VALUES (:id, :id_enfant, :proportion)"; 
    $exe = $db->prepare($sql);

    $exe->bindParam(':id', $id);
    $exe->bindParam(':id_enfant', $id_enfant);
    $exe->bindParam(':proportion', $proportion);

    if ($exe->execute()) {
        $res = [
            'id_aliment' => $id,
            'id_aliment_enfant' => $id_enfant,
            'proportion' => $proportion];
        http_response_code(201);
    } else {
        $res = ["error" => "Failed to create subaliment."];
        http_response_code(500);
    }
    return $res;
}

function update_subaliment($db, $id, $id_enfant, $proportion) {
    $sql = "UPDATE est_compose_de SET proportion = :proportion WHERE id_aliment = :id AND id_aliment_enfant = :id_enfant";
    $exe = $db->prepare($sql);

    $exe->bindParam(':id', $id);
    $exe->bindParam(':id_enfant', $id_enfant);
    $exe->bindParam(':proportion', $proportion);

    if ($exe->execute()) {
        $rowCount = $exe->rowCount();

        if ($rowCount > 0) {
            $res = [
                'id_aliment' => $id,
                'id_aliment_enfant' => $id_enfant,
                'proportion' => $proportion];
            http_response_code(200);
        } else {
            $res = ['error' => "Subaliment not found or no changes made."];
            http_response_code(404);
        }
    } else {
        $res = ["error" => "Failed to update subaliment."];
        http_response_code(500);
    }

    return $res;
}

function delete_subaliment($db, $id, $id_enfant) {
    $sql = "DELETE FROM est_compose_de WHERE id_aliment = :id AND id_aliment_enfant = :id_enfant"; 
    $exe = $db->prepare($sql);

    $exe->bindParam(':id', $id);
    $exe->bindParam(':id_enfant', $id_enfant);

    if ($exe->execute()) {
        if ($exe->rowCount() > 0) {
            $res = [
                "message" => "Subaliment deleted successfully.",
                "id" => $id];
            http_response_code(200);
        } else {
            $res = ["error" => "Subaliment not found."];
            http_response_code(404);
        }
    } else {
        $res = ["error" => "Failed to delete subaliment."];
        http_response_code(500);
    }

    return $res;
}

switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        $result = get_subaliments($pdo, $inputArray[0] ?? '', $inputArray[1] ?? '');
        setHeaders();
        echo json_encode($result);
        exit;
        
    case 'POST':
        $postData = json_decode(file_get_contents("php://input"), true);
        if (isset($postData['libelle']) && isset($postData['id_type'])) {
            $result = new_subaliment($pdo, $inputArray[0] ?? '', $inputArray[1] ?? '', $postData['proportion']);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid data provided."]);
        }
        exit;

    case 'PUT':
        $putData = json_decode(file_get_contents("php://input"), true);
        if (isset($inputArray[0], $putData['id_type'], $putData['libelle'])) {
            $result = update_subaliment($pdo, $inputArray[0] ?? '', $inputArray[1] ?? '', $postData['proportion']);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid data provided."]);
        }
        exit;
    
    case 'DELETE':
        $result = delete_subaliment($pdo, $inputArray[0], $inputArray[1]);
        echo json_encode($result);
        exit;
        
    default:
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
        http_response_code(405);
        exit(json_encode(["error" => "Method Not Allowed"]));
}