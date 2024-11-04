<?php
    function renderSideMenuToHTML($currentPageId) {
        $mymenu = array(
            'index' => 'Accueil' ,
            'profil' => 'Profil' ,
            'aliments' => 'Aliments',
            'journal' => 'Journal'
        );
        
        echo "<div class=\"sidemenu\" onmouseenter=\"openSM(this)\" onmouseleave=\"closeSM(this)\">";
        echo ('<p href="" class="openSideMenu">></p>');
        echo ('<p href="" class="closeSideMenu"><</p>');
        foreach($mymenu as $pageId => $pageParameters) {
            $CurrentPageString = "";
            if ($currentPageId == $pageId){
                $CurrentPageString = ' id="currentpage"';
                $page = "";
            }
            else {
                $page = $pageId . ".php";
            }
            $titre = $pageParameters;
            echo ('<a' . $CurrentPageString . ' href="' . $page . '">' . $titre . '</a>');
        }
        echo ('<a href="deconnection.php" class="deconnexion">Déconnection</a>');
        echo "</div><script>function openSM(x) {x.style.width = \"250px\";x.querySelector('.openSideMenu').style.visibility = 'hidden';x.querySelector('.closeSideMenu').style.visibility = 'visible';}function closeSM(x) {x.style.width = \"25px\";x.querySelector('.closeSideMenu').style.visibility = 'hidden';x.querySelector('.openSideMenu').style.visibility = 'visible';}</script>";
    }
?>