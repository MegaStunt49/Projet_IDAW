<?php
    require_once('../../init_pdo.php');
    $row = 1;
    $row2 = 1;
    if (($handle = fopen("alimentsCropped.csv", "r")) !== FALSE) {
        $data = fgetcsv($handle, 1000, ";");
        $num = count($data);
        echo "<table id=\"myTable\" class=\"display\"><thead><tr>";
        $header = [];
        for ($c=0; $c < $num; $c++){
            echo "<th>".$data[$c]."</th>";
            $header[] = $data[$c];
        }
        echo "</tr></thead><tbody>";
        $nomGroup = [];
        $nomAlim = [];
        $corGroup = [];
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $num = count($data);
            $row++;
            echo "<tr>";
            if (!in_array($data[0],$nomGroup) && strlen($data[0])>0){
                $nomGroup[] = $data[0];
                $requestID = $pdo->query("SELECT id_type_aliment FROM type_d_aliment ORDER BY `type_d_aliment`.`id_type_aliment` DESC");
                $dataid = $requestID->fetch(PDO::FETCH_OBJ);
                $requestPost = $pdo->prepare("INSERT INTO type_d_aliment (id_type_aliment, libelle) VALUES ('".($dataid->id_type_aliment + 1)."', '".$data[0]."')");
                $requestPost->execute();
            }
            if (!in_array($data[1],$nomAlim) && strlen($data[1])>0){
                $nomAlim[] = $data[1];
                $corGroup[] = $data[0];
                $requestID = $pdo->query("SELECT id_type_aliment FROM type_d_aliment WHERE libelle = '".$data[0]."'");
                $dataid = $requestID->fetch(PDO::FETCH_OBJ);
                $sql = "INSERT INTO aliment (id_type_aliment, libelle) VALUES ('".$dataid->id_type_aliment."', '".$data[1]."')"; 
                $exe = $pdo->prepare($sql);
                $exe->execute();
            }
            // $requestIDal = $pdo->query("SELECT id_aliment FROM aliment WHERE libelle = '".$data[1]."'");
            // $dataidal = $requestIDal->fetch(PDO::FETCH_OBJ);
            for ($c=2; $c < $num; $c++) {
                echo "<td>".$data[$c]."</td>";
            //     if (ctype_digit($data[$c][0])){
            //         $requestIDcar = $pdo->query("SELECT id_caracteristique FROM caracteristique WHERE libelle = '".$header[$c]."'");
            //         $dataidcar = $requestIDcar->fetch(PDO::FETCH_OBJ);
            //         $sql = "INSERT INTO contient_pour_100g (id_aliment, id_caracteristique, libelle) VALUES ('".$dataidal->id_aliment."', '".$dataidcar->id_caracteristique."', '".$data[$c]."')"; 
            //         $exe = $pdo->prepare($sql);
            //         $exe->execute();
            //     }
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
        $num2 = count($nomGroup);
        echo "<p>".$num2."</p>";
        for ($c=0; $c < $num2; $c++) {
            echo "<p>".$nomGroup[$c]."</p>";
        }
        $num3 = count($nomAlim);
        echo "<p>".$num3."</p>";
        fclose($handle);
    }

    $pdo = null;
?>