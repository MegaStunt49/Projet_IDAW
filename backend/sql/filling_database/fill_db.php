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
        $nomGroup = [];
        $nomAlim = [];
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $num = count($data);
            $row++;
            echo "<tr>";
            if (!in_array($data[0],$nomGroup)){
                $nomGroup[] = $data[0];
            }
            if (!in_array($data[1],$nomAlim)){
                $nomAlim[] = $data[1];
            }
            for ($c=0; $c < $num; $c++) {
                echo "<td>".$data[$c]."</td>";

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
        echo "<p>".$nomAlim[0]."</p>";
        echo "<p>".$nomAlim[3150]."</p>";
        echo "<p>".$nomAlim[3170]."</p>";
        echo "<p>".$nomAlim[$num3-1]."</p>";
        fclose($handle);
    }

    $pdo = null;
?>