<?php
    session_start();

    if(!isset($_SESSION['login']) && !isset($_SESSION['password'])) {
        header('Location: connection.php');
    }


    require_once('templates/template_header.php');

    $currentPageId = "index";

    require_once('templates/template_sidemenu.php');
    renderSideMenuToHTML($currentPageId);
?>
<script src="https://d3js.org/d3.v6.min.js"></script>
<script src="js/index.js"></script>

<div id="main">
    <h1>iMangerMieux</h1>
    <p class="large-text">Bienvenue, <span id="username-holder"></span></p>
    <div class="vertical-container">   
        <div class="section">
            <h2 class="section-title">Consommation des types d'aliments sur <span id="periode-holder-1">7 jours</span></h2>
            <div class="horizontal-container">
                <span id="aliment-type-chart" style="flex:1"></span>
                <p style="flex:1" class="large-text">Au petit-déjeuner, il est conseillé de privilégier des aliments riches en fibres comme les céréales complètes, 
                    les fruits frais, et une source de protéines légères, telles que le yaourt ou les œufs. 
                    Ces aliments fournissent de l'énergie durable pour commencer la journée et aident à réguler la glycémie. 
                    Au déjeuner, une portion de légumes, une source de protéines (comme la viande maigre, le poisson ou les légumineuses), 
                    et des glucides complexes (comme le riz complet ou les pommes de terre) sont recommandés pour un apport équilibré en nutriments.
                    Enfin, pour le dîner, il est conseillé de consommer un repas léger pour faciliter la digestion avant le sommeil, 
                    avec des légumes, des protéines végétales ou animales en petite quantité, et des glucides lents.
                </p>
            </div>
        </div>
        <div class="section">
            <h2 class="section-title">Apports caloriques et nutriments sur <span id="periode-holder-2">7 jours</span></h2>
            <div class="horizontal-container">
                <div class="vertical-container" style="flex:1">
                    <span id="apports-energie-chart" style="flex:1"></span>
                    <p style="flex:1">
                        Apports energétiques recommandés par l'oms :
                    </p>
                </div>
                <div class="vertical-container" style="flex:1">
                    <span id="nutriments-chart" style="flex:1"></span>
                    <p style="flex:1">
                        Apports en nutriments recommandés par l'oms :
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    require_once("templates/template_footer.php");
?>