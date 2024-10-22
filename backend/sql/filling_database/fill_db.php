<?php
    require_once('../../init_pdo.php');
    $row = 1;
    if (($handle = fopen("alimentsCropped.csv", "r")) !== FALSE) {
        $data = fgetcsv($handle, 1000, ";");
        $num = count($data);
        echo "<table id=\"myTable\" class=\"display\"><thead><tr>";
        for ($c=0; $c < $num; $c++){
            echo "<th>".$data[$c]."</th>";
        }
        echo "</tr></thead><tbody>";
        
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $num = count($data);
            $row++;
            echo "<tr>";
            for ($c=0; $c < $num; $c++) {
                echo "<td>".$data[$c]."</td>";

            }
            echo "</tr>";
        }
        echo "</tbody></table>";

        fclose($handle);
    }

    $pdo = null;
?>