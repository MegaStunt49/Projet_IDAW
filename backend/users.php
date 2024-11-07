<?php
require_once("init_pdo.php");

// function setHeaders() {
//     // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
//     header("Access-Control-Allow-Origin: *");
//     header('Content-type: application/json; charset=utf-8');
// }

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

function getJsonInput() {
    $json = file_get_contents('php://input');
    return json_decode($json, true);
}

function get_users($db, $login) {
    session_start();
    if(isset($_SESSION["est_admin"]) && $_SESSION["est_admin"] == 1) {
        if(isset($login) && $login != '') {
            $sql = "SELECT utilisateur.login, niveau_sportif.libelle AS sportlibelle, sexe.libelle AS sexelibelle, utilisateur.annee_naissance, utilisateur.pseudo, utilisateur.email 
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
            $sql = "SELECT utilisateur.login, niveau_sportif.libelle AS sportlibelle, sexe.libelle AS sexelibelle, utilisateur.annee_naissance, utilisateur.pseudo, utilisateur.email 
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
    } elseif(isset($_SESSION['login'])) {
        $trueLogin = $_SESSION['login'];
        $sql = "SELECT utilisateur.login, niveau_sportif.libelle AS sportlibelle, sexe.libelle AS sexelibelle, utilisateur.annee_naissance, utilisateur.pseudo, utilisateur.email 
        FROM utilisateur, niveau_sportif, sexe 
        WHERE utilisateur.id_niveau = niveau_sportif.id_niveau AND utilisateur.id_sexe = sexe.id_sexe AND utilisateur.login = :login";        
        $exe = $db->prepare($sql); 
        
        $exe->bindParam(':login', $trueLogin);
        if ($exe->execute()) {
            $res = $exe->fetchAll(PDO::FETCH_OBJ);
            http_response_code(201);
        } else {
            $res = ["error" => "Failed to fetch users."];
            http_response_code(500);
        }
    }
    else {
        $res = ["error" => "Failed to fetch users."];
        http_response_code(500);
    }
    
    return $res;
}

function new_user($db, $data) {
    $sql = "INSERT INTO utilisateur (login, id_niveau, id_sexe, password, annee_naissance, pseudo, email) 
            VALUES (:login, :id_niveau, :id_sexe, :password, :annee_naissance, :pseudo, :email)";
    $exe = $db->prepare($sql);

    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    $exe->bindParam(':login', $data['login']);
    $exe->bindParam(':id_niveau', $data['id_niveau']);
    $exe->bindParam(':id_sexe', $data['id_sexe']);
    $exe->bindParam(':password', $hashedPassword);
    $exe->bindParam(':annee_naissance', $data['annee_naissance']);
    $exe->bindParam(':pseudo', $data['pseudo']);
    $exe->bindParam(':email', $data['email']);

    if ($exe->execute()) {
        $res = [
            'login' => $data['login'],
            'id_niveau' => $data['id_niveau'],
            'id_sexe' => $data['id_sexe'],
            'annee_naissance' => $data['annee_naissance'],
            'pseudo' => $data['pseudo'],
            'email' => $data['email']
        ];
        http_response_code(201);
    } else {
        $res = ["error" => "Failed to create user."];
        http_response_code(500);
    }
    return $res;
}

function update_user($db, $data) {
    session_start();
    if (isset($_SESSION['login']) && $_SESSION['login'] == $data['login'] || (isset($_SESSION['est_admin']) && boolval($_SESSION["est_admin"]))) {
        $sql = "UPDATE utilisateur SET id_niveau = :id_niveau, password = :password, pseudo = :pseudo, email = :email, annee_naissance = :annee_naissance, id_sexe = :id_sexe
                WHERE login = :login";
        $exe = $db->prepare($sql);

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $exe->bindParam(':login', $data['login']);
        $exe->bindParam(':id_niveau', $data['id_niveau']);
        $exe->bindParam(':password', $hashedPassword);
        $exe->bindParam(':pseudo', $data['pseudo']);
        $exe->bindParam(':email', $data['email']);
        $exe->bindParam(':annee_naissance', $data['annee_naissance']);
        $exe->bindParam(':id_sexe', $data['id_sexe']);

        if ($exe->execute()) {
            $rowCount = $exe->rowCount();
            if ($rowCount > 0) {
                $res = [
                    'login' => $data['login'],
                    'id_niveau' => $data['id_niveau'],
                    'id_sexe' => $data['id_sexe'],
                    'annee_naissance' => $data['annee_naissance'],
                    'pseudo' => $data['pseudo'],
                    'email' => $data['email']
                ];
                http_response_code(200);
            } else {
                $res = ['error' => "User not found or no changes made."];
                http_response_code(404);
            }
        } else {
            $res = ["error" => "Failed to update user."];
            http_response_code(500);
        }
    } else {
        $res = ["error" => "No rights to update user."];
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
        // setHeaders();
        echo json_encode($result);
        exit;
        
    case 'POST':
        $data = getJsonInput();
        if ($data) {
            $result = new_user($pdo, $data);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid data provided."]);
        }
        break;
        
    case 'PUT':
        $data = getJsonInput();
        if ($data) {
            $result = update_user($pdo, $data);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid data provided."]);
        }
        break;
    
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
