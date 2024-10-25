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
        2 => '',
        3 => '',
        4 => '',
        5 => '',
        6 => ''
    ];
}

function get_users($db, $login) {
    if(isset($login) && $login != '') {
        $sql = "SELECT utilisateur.login, niveau_sportif.libelle, sexe.libelle, utilisateur.annee_naissance, utilisateur.pseudo, utilisateur.email 
        FROM utilisateur, niveau_sportif, sexe 
        WHERE utilisateur.id_niveau = niveau_sportif.id_niveau AND utilisateur.id_sexe = sexe.id_sexe AND utilisateur.login = :login"; 
        $exe = $db->prepare($sql);
    
        $exe->bindParam(':login', $login);
        if ($exe->execute()) {
            $res = $exe->fetch(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch user."];
            http_response_code(500);
        }
        
    } else {
        $sql = "SELECT utilisateur.login, niveau_sportif.libelle, sexe.libelle, utilisateur.annee_naissance, utilisateur.pseudo, utilisateur.email 
        FROM utilisateur, niveau_sportif, sexe 
        WHERE utilisateur.id_niveau = niveau_sportif.id_niveau AND utilisateur.id_sexe = sexe.id_sexe";        
        
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

function new_user($db, $login, $id_niveau, $id_sexe, $password, $annee_naissance, $pseudo, $email) {
    $sql = "INSERT INTO utilisateur (login, id_niveau, id_sexe, password, annee_naissance, pseudo, email) 
    VALUES (:login, :id_niveau, :id_sexe, :password, :annee_naissance, :pseudo, :email)";
    $exe = $db->prepare($sql);

    $exe->bindParam(':login', $login);
    $exe->bindParam(':id_niveau', $id_niveau);
    $exe->bindParam(':id_sexe', $id_sexe);
    $exe->bindParam(':password', $password);
    $exe->bindParam(':annee_naissance', $annee_naissance);
    $exe->bindParam(':pseudo', $pseudo);
    $exe->bindParam(':email', $email);

    if ($exe->execute()) {
        $newUserId = $db->lastInsertId();
        $res = [
            'login' => $login,
            'id_niveau' => $id_niveau,
            'id_sexe' => $id_sexe,
            'password' => $password,
            'annee_naissance' => $annee_naissance,
            'pseudo' => $pseudo,
            'email' => $email];
        http_response_code(201);
    } else {
        $res = ["error" => "Failed to create user."];
        http_response_code(500);
    }
    return $res;
}

function update_user($db, $login, $id_niveau, $password, $pseudo, $email) {
    $sql = "UPDATE utilisateur SET id_niveau = :id_niveau, password = :password, pseudo = :pseudo, email = :email
    WHERE login = :login";
    $exe = $db->prepare($sql);

    $exe->bindParam(':login', $login);
    $exe->bindParam(':id_niveau', $id_niveau);
    $exe->bindParam(':password', $password);
    $exe->bindParam(':pseudo', $pseudo);
    $exe->bindParam(':email', $email);

    if ($exe->execute()) {
        $rowCount = $exe->rowCount();

        if ($rowCount > 0) {
            $res = [
                'login' => $login,
                'id_niveau' => $id_niveau,
                'password' => $password,
                'pseudo' => $pseudo,
                'email' => $email];
            http_response_code(200);
        } else {
            $res = ['error' => "User not found or no changes made."];
            http_response_code(404);
        }
    } else {
        $res = ["error" => "Failed to update user."];
        http_response_code(500);
    }

    return $res;
}

function delete_user($db, $login) {
    $sql = "DELETE FROM utilisateur WHERE utilisateur.login = :login"; 
    $exe = $db->prepare($sql);

    $exe->bindParam(':login', $login);

    if ($exe->execute()) {
        if ($exe->rowCount() > 0) {
            $res = [
                "message" => "User deleted successfully.",
                "login" => $login];
            http_response_code(200);
        } else {
            $res = ["error" => "User not found."];
            http_response_code(404);
        }
    } else {
        $res = ["error" => "Failed to delete user."];
        http_response_code(500);
    }

    return $res;
}

switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        $result = get_users($pdo, $inputArray[0]);
        setHeaders();
        echo json_encode($result);
        exit;
        
    case 'POST':
        $result = new_user($pdo, $inputArray[0], $inputArray[1], $inputArray[2], $inputArray[3], $inputArray[4], $inputArray[5], $inputArray[6]);
        echo json_encode($result);
        exit;
        
    case 'PUT':
        $result = update_user($pdo, $inputArray[0], $inputArray[1], $inputArray[2], $inputArray[3], $inputArray[4]);
        echo json_encode($result);
        exit;
    
    case 'DELETE':
        $result = delete_user($pdo, $inputArray[0]);
        echo json_encode($result);
        exit;
        
    default:
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
        http_response_code(405);
        exit(json_encode(["error" => "Method Not Allowed"]));
}
?>
