<?php
    function renderSideMenuToHTML($currentPageId) {
        $mymenu = array(
            'accueil' => 'Accueil' ,
            'cv' => 'CV' ,
            'projets' => 'Projets',
            'infos_techniques' => 'Infos',
            'contact' => 'Contact'
        );
        
        echo "<div class=\"sidemenu\" onmouseenter=\"openSM(this)\" onmouseleave=\"closeSM(this)\">";

        foreach($mymenu as $pageId => $pageParameters) {
            $CurrentPageString = "";
            if ($currentPageId == $pageId){
                $CurrentPageString = ' id="currentpage"';
            }
            $titre = $pageParameters;
            echo ('<a' . $CurrentPageString . ' href="' . $pageId . '.php">' . $titre . '</a>');
        }
        
        echo "</div><script>function openSM(x) {x.style.width = \"250px\";}function closeSM(x) {x.style.width = \"10px\";}</script>";
    }
?>