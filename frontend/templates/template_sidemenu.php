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
        echo ('<a href="#" onClick="Deconect();" class="deconnexion">Déconnexion</a>');
        echo '<div id="config" data-api-prefix="'. _PREFIX . '"></div>';
        echo "</div>
                <script>
                function openSM(x) {
                    x.style.width = \"250px\";
                    document.getElementById(\"main\").style.marginLeft= \"250px\";
                    x.querySelector('.openSideMenu').style.visibility = 'hidden';
                    x.querySelector('.closeSideMenu').style.visibility = 'visible';
                }
                function closeSM(x) {
                    x.style.width = \"25px\";
                    document.getElementById(\"main\").style.marginLeft= \"25px\";
                    x.querySelector('.closeSideMenu').style.visibility = 'hidden';
                    x.querySelector('.openSideMenu').style.visibility = 'visible';
                }
                function Deconect() {
                    Disonnect(\""._PREFIX."\");
                    location.href = \"connection.php\";
                }
                </script>";
        echo "<script src=\"js/connectRedirect.js\"></script>";
    }
?>