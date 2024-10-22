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
        $sql = "SELECT * FROM aliment WHERE ID_ALIMENT=:id"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':id', $id);
        $exe->execute();
        $res = $exe->fetch(PDO::FETCH_OBJ);
    } else {
        $sql = "SELECT * FROM aliment"; 
        $exe = $db->query($sql); 
        $res = $exe->fetchAll(PDO::FETCH_OBJ);
    }
    
    return $res;
}

function new_aliment($db, $libelle, $id_type) {
    $sql = "INSERT INTO aliment (ID_TYPE_ALIMENT, LIBELLE) VALUES (:id_type, :libelle)"; 
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
    $sql = "UPDATE aliment SET LIBELLE = :libelle, ID_TYPE_ALIMENT = :id_type WHERE ID_ALIMENT = :id";
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
    $sql = "DELETE FROM aliment WHERE ID_ALIMENT = :id"; 
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
        $result = new_aliment($pdo, $inputArray[0], $inputArray[1]);
        echo json_encode($result);
        exit;
        
    case 'PUT':
        $result = update_aliment($pdo, $inputArray[0], $inputArray[1], $inputArray[2]);
        echo json_encode($result);
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