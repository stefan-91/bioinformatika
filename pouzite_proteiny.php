<?php
error_reporting(E_ALL);
ini_set("display_errors","On");
// http://localhost/DiplomovaPraca/vyhladavac/

?>

<html lang="en">
	<head>
        <link rel="stylesheet" type="text/css" href="style.css"> 
        <!--  link rel="icon" type="image/png" href="favicon.png" /-->
        <title>Protein-protein interaction (PPI)</title>
	</head>	
	<body>
	
	<h1>Vyhľadávač interakcií medzi proteínmi v odborných textoch</h1>
	<h2>Použité názvy proteínov</h2>

<?php 
vypisPouziteInterakcie();

function vypisPouziteInterakcie() {
    $pocetSlovNaRiadok = 8;
    $dlzkaSlova = 20;
    
    //------- Vyber databazy -------------
    $nazovDB = '';
    if(isset($_POST['volbaDB'])) $nazovDB = $_POST['volbaDB'];
    else $nazovDB = 'grgt_tuke_2020';
    
    echo '<div class="formular">';
    echo '<form action="pouzite_proteiny.php" method="POST">';
    
    echo 'Zvoľte databázu:<br/>';
    
    // Vyberie co bude zaskrtnute
    $grgt2020 = '';
    $co2018 = '';
    if(strcmp($nazovDB,'grgt_tuke_2020') == 0) $grgt2020 = 'checked';
    else $co2018 = 'checked';
    
    
    
    echo '<input type="radio" id="grgt_tuke_2020" name="volbaDB" value="grgt_tuke_2020" '.$grgt2020.'>';
    echo '<label for="grgt_tuke_2020">GRGT TUKE 2020</label><br>';
    echo '<input type="radio" id="co_occurence_tuke_2018" name="volbaDB" value="co_occurence_tuke_2018" '.$co2018.'>';
    echo '<label for="co_occurence_tuke_2018">Co-occurence TUKE 2018</label><br>';
    echo '<br>';
    
    echo '<input type="submit" value = "Vypísať použité proteíny">';
    
    echo '</form>';
    echo '</div>';
    
    //------------------------------------  
    
    require_once 'Databaza.php';
    $db = new Databaza($nazovDB);
    
    $sql = "SELECT DISTINCT nazov FROM Pouzite_proteiny ORDER BY nazov ASC;";
    $vysledky = $db->vratPole($sql);
    
    //echo '<pre>'; print_r($vysledky); echo '</pre>';
    $riadok = '';
    $pocet = 0;
    echo '<div class="vypispouziteho">';
    foreach($vysledky as $vysledok) {
        $slovo = $vysledok['nazov'];
        $rozdiel = $dlzkaSlova - strlen($slovo);
        //echo "rozdiel: " . $rozdiel . "<br/>";
        
        $dokladame = '';
        if($rozdiel >= 1) {
            for($i=0; $i<$rozdiel; $i++) {
                $dokladame = $dokladame . '&nbsp;';
            }
        }
        $slovo = $slovo . $dokladame;
        //echo "|" . $dokladame . "| <br/>";
        
        $riadok = $riadok . ' ' . strtolower($slovo);
        $pocet++;
                
        echo $riadok;
        $riadok = '';
    }    
}

?>
	</body>
</html>























































