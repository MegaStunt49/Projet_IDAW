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
            $idParam = "NULL";
            $idParam3 = "NULL";
            if (!in_array($data[0],$nomGroup) && strlen($data[0])>0){
                $nomGroup[] = $data[0];
                $requestID = $pdo->query("SELECT id_type_aliment FROM type_d_aliment ORDER BY `type_d_aliment`.`id_type_aliment` DESC");
                $dataid = $requestID->fetch(PDO::FETCH_OBJ);
                $exe = $pdo->prepare("INSERT INTO type_d_aliment (id_type_aliment, libelle) VALUES (:id_type, :libelle)");

                $idParam3 = ($dataid->id_type_aliment + 1);

                $exe->bindParam(':id_type', $idParam3);
                $exe->bindParam(':libelle', $data[0]);

                $exe->execute();
            }
            if (!in_array($data[1],$nomAlim) && strlen($data[1])>0){
                $nomAlim[] = $data[1];
                $corGroup[] = $data[0];
                $requestID = $pdo->query("SELECT id_type_aliment FROM type_d_aliment WHERE libelle = '".$data[0]."'");
                $dataid = $requestID->fetch(PDO::FETCH_OBJ);
                $sql = "INSERT INTO aliment (id_type_aliment, libelle) VALUES (:id_type, :libelle)"; 
                $exe = $pdo->prepare($sql);

                $idParam = $dataid->id_type_aliment;

                $exe->bindParam(':id_type', $idParam);
                $exe->bindParam(':libelle', $data[1]);

                $exe->execute();
            }
            $requestIDal = $pdo->prepare("SELECT id_aliment FROM aliment WHERE libelle = :libelle");
            $requestIDal->bindParam(':libelle', $data[1]);
            $requestIDal->execute();
            $dataidal = $requestIDal->fetch(PDO::FETCH_OBJ);
            $idParam1 = $dataidal->id_aliment;
            for ($c=2; $c < $num; $c++) {
                echo "<td>".$data[$c]."</td>";
                if ($data[$c]!="" && ctype_digit($data[$c][0])){
                    $requestIDcar = $pdo->prepare("SELECT id_caracteristique FROM caracteristique WHERE libelle = '".$header[$c]."'");
                    $requestIDcar->execute();
                    $dataidcar = $requestIDcar->fetch(PDO::FETCH_OBJ);
                    $sql = "INSERT INTO contient_pour_100g (id_aliment, id_caracteristique, quantite) VALUES ( :id, :id_caracteristique, :quantite)"; 
                    $exe = $pdo->prepare($sql);
                    $idParam2 = $dataidcar->id_caracteristique;
                    $Param4 = floatval(str_replace(",",".",$data[$c]));
                    $exe->bindParam(':id', $idParam1);
                    $exe->bindParam(':id_caracteristique', $idParam2);
                    $exe->bindParam(':quantite', $Param4);
                    $exe->execute();
                }
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