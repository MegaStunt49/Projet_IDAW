<?php
    function renderSideMenuToHTML($currentPageId) {
        $mymenu = array(
            'index' => 'Accueil' ,
            'profil' => 'Profil' ,
            'aliments' => 'Aliments',
            'journal' => 'Journal'
        );
        
        echo "<div class=\"sidemenu\" onmouseenter=\"openSM(this)\" onmouseleave=\"closeSM(this)\">";

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
        
        echo "</div><script>function openSM(x) {x.style.width = \"250px\";}function closeSM(x) {x.style.width = \"20px\";}</script>";
    }
?>