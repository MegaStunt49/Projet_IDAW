<?php

use LDAP\Result;

require_once("init_pdo.php");

function setHeaders() {
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
}

$inputArray = [
    0 => '',
    1 => '',
];
if(isset($_SERVER['PATH_INFO'])) {
    $cleanedString = trim($_SERVER['PATH_INFO'], '/');
    $inputArray = explode('/', $cleanedString);
}

function get_type_aliment($db, $id) {
    if(isset($id) && $id != '') {
        $sql = "SELECT * FROM type_d_aliment WHERE type_d_aliment.id_type_aliment=:id"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':id', $id);

        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch type."];
            http_response_code(500);
        }
    } else {
        $sql = "SELECT * FROM type_d_aliment";

        $exe = $db->query($sql); 
        if ($exe) {
            $res = $exe->fetchAll(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch type."];
            http_response_code(500);
        }
    }
    
    return $res;
}

function get_niveau($db, $id) {
    if(isset($id) && $id != '') {
        $sql = "SELECT * FROM niveau_sportif WHERE niveau_sportif.id_niveau=:id"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':id', $id);

        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch niveau."];
            http_response_code(500);
        }
    } else {
        $sql = "SELECT * FROM niveau_sportif";

        $exe = $db->query($sql); 
        if ($exe) {
            $res = $exe->fetchAll(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch niveaux."];
            http_response_code(500);
        }
    }
    
    return $res;
}

function get_sexe($db, $id) {
    if(isset($id) && $id != '') {
        $sql = "SELECT * FROM sexe WHERE sexe.id_sexe=:id"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':id', $id);

        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch sexe."];
            http_response_code(500);
        }
    } else {
        $sql = "SELECT * FROM sexe";
        
        $exe = $db->query($sql); 
        if ($exe) {
            $res = $exe->fetchAll(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch sexes."];
            http_response_code(500);
        }
    }
    
    return $res;
}

function get_unite($db, $id) {
    if(isset($id) && $id != '') {
        $sql = "SELECT * FROM unite WHERE unite.id_unite=:id"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':id', $id);

        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch unite."];
            http_response_code(500);
        }
    } else {
        $sql = "SELECT * FROM unite";
        
        $exe = $db->query($sql); 
        if ($exe) {
            $res = $exe->fetchAll(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch unites."];
            http_response_code(500);
        }
    }
    
    return $res;
}

function get_caracteristique($db, $id) {
    if(isset($id) && $id != '') {
        $sql = "SELECT caracteristique.id_caracteristique, caracteristique.libelle, unite.libelle AS nom_unite 
        FROM caracteristique, unite 
        WHERE caracteristique.id_unite = unite.id_unite AND caracteristique.id_caracteristique=:id"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':id', $id);

        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch user."];
            http_response_code(500);
        }
    } else {
        $sql = "SELECT caracteristique.id_caracteristique, caracteristique.libelle, unite.libelle AS nom_unite 
        FROM caracteristique, unite 
        WHERE caracteristique.id_unite = unite.id_unite"; 
        
        $exe = $db->query($sql); 
        if ($exe) {
            $res = $exe->fetchAll(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch users."];
            http_response_code(500);
        }
    }
    
    return $res;
}

switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        setHeaders();
        switch($inputArray[0]) {
            case 'caracteristique':
                $result = get_caracteristique($pdo, $inputArray[1] ?? '');
                echo json_encode($result);
                exit;

            case 'unite':
                $result = get_unite($pdo, $inputArray[1] ?? '');
                echo json_encode($result);
                exit;

            case 'sexe':
                $result = get_sexe($pdo, $inputArray[1] ?? '');
                echo json_encode($result);
                exit;

            case 'niveau':
                $result = get_niveau($pdo, $inputArray[1] ?? '');
                echo json_encode($result);
                exit;

            case 'type-aliment':
                $result = get_type_aliment($pdo, $inputArray[1] ?? '');
                echo json_encode($result);
                exit;

            default:
                http_response_code(404);
                exit(json_encode(["error" => "This reference does not exist"]));
            
        }
        exit;
        
    default:
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
        http_response_code(405);
        exit(json_encode(["error" => "Method Not Allowed"]));
}