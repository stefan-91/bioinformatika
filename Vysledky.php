<?php
error_reporting(E_ALL);
ini_set("display_errors","On");

class Vysledky {
    private $protein1 = null;
    private $interakcia = null;
    private $protein2 = null;
    private $nazovDB = null;
    
    public function __construct() {
        // Nacita udaje z formulara ak existuju
        if(isset($_POST['protein1'])) $this->protein1 = trim($_POST['protein1']);
        if(isset($_POST['interactionWord'])) $this->interakcia = trim($_POST['interactionWord']);
        if(isset($_POST['protein2'])) $this->protein2 = trim($_POST['protein2']);
        
        if(isset($_POST['volbaDB'])) $this->nazovDB = $_POST['volbaDB'];
        else $this->nazovDB = 'grgt_tuke_2020';
    }
    
    public function vykresliVysledky() {        
        //require 'vendor/autoload.php';
        
        // Vyberie databazu
        
        
        require_once 'Databaza.php';
        $db = new Databaza($this->nazovDB);
                       
        // Ochrana pred SQL injection
            // Ak nenajde zhodu v programe, tak do databazy s neznamym textom nejde
        $proteinyArr = $db->vratPole("SELECT DISTINCT nazov FROM Pouzite_proteiny;");
        $interakcieArr = $db->vratPole("SELECT DISTINCT nazov FROM Pouzite_interakcie;");
            // Kontrola proteinov na vstupe
        $nasloP1 = false;
        $nasloP2 = false;
        foreach($proteinyArr as $ntica) {
            if(strcmp(strtolower($this->protein1), strtolower($ntica['nazov'])) == 0) $nasloP1 = true;
            if(strcmp(strtolower($this->protein2), strtolower($ntica['nazov'])) == 0) $nasloP2 = true;
        }
        if($nasloP1 == false) $this->protein1 = null;
        if($nasloP2 == false) $this->protein2 = null;
            // Kontrola interakcii na vstupe        
        $nasloI = false;        
        foreach($interakcieArr as $ntica) {
            if(strcmp(strtolower($this->interakcia), strtolower($ntica['nazov'])) == 0) $nasloI = true;
        }
        if($nasloI == false) $this->interakcia = null;
        
        // Zostavi podmienku
        $podmienka = "";
        if($this->protein1 != null) $podmienka = $podmienka . ' AND veta LIKE "%<protein>'. $this->protein1 .'</protein>%"';
        if($this->interakcia != null) $podmienka = $podmienka . ' AND veta LIKE "%<interakcia>'. $this->interakcia .'</interakcia>%"';
        if($this->protein2 != null) $podmienka = $podmienka . ' AND veta LIKE "%<protein>'. $this->protein2 .'</protein>%"';
        $podmienka = substr($podmienka, 5); // odstrani prve slobo "AND"
        $podmienka = trim($podmienka);
        //echo 'Podmienka: |'.$podmienka.'| ' . strlen($podmienka) . '<br/>';
        
        $vysledky = array();
        if(strlen($podmienka) > 0) {
            $sql = "SELECT DISTINCT * FROM Anotovane_vety WHERE $podmienka;";
            //echo $sql . '<br/>';
            $vysledky = $db->vratPole($sql);
        }                                   
        echo 'Počet výsledkov: ' . count($vysledky) . '<br/>';
        
        //echo '<pre>'; print_r($vysledky); echo '</pre>';
        foreach($vysledky as $vysledok) {
            $veta = $vysledok['veta'];
            $veta = str_replace('<protein>', '<span class="protein">', $veta);
            $veta = str_replace('</protein>', '</span>', $veta);
            
            $veta = str_replace('<interakcia>', '<span class="nazovInterakcie">', $veta);
            $veta = str_replace('</interakcia>', '</span>', $veta);
            
            $url = $vysledok['url'];
            $url = str_replace('pdf/', '', $url);
            
            echo '<p>';
            echo $veta;
            echo '<br/>';
            echo '<a target="_blank" href="' . $url . '" >(odkaz na pôvodný text)</a>' . '<br/>';
            echo '</p>';
            echo '<hr/>';            
        }
    }

    public function vykresliVyhladavaciFormular() {
        echo '<div class="formular">';
        echo '<form action="index.php" method="POST">';
        
        echo 'Zvoľte databázu:<br/>';
        
        // Vyberie co bude zaskrtnute
        $grgt2020 = '';
        $co2018 = '';        
        if(strcmp($this->nazovDB,'grgt_tuke_2020') == 0) $grgt2020 = 'checked';
        else $co2018 = 'checked';

        echo '<input type="radio" id="grgt_tuke_2020" name="volbaDB" value="grgt_tuke_2020" '.$grgt2020.'>';
        echo '<label for="grgt_tuke_2020">GRGT TUKE 2020</label><br>';
        echo '<input type="radio" id="co_occurence_tuke_2018" name="volbaDB" value="co_occurence_tuke_2018" '.$co2018.'>';
        echo '<label for="co_occurence_tuke_2018">Co-occurence TUKE 2018</label><br>';
        echo '<br>';
        
        $protein1 = '';
        $interakcia = '';
        $protein2 = '';
        if($this->protein1 != null) $protein1 = $this->protein1;
        if($this->interakcia != null) $interakcia =  $this->interakcia;
        if($this->protein2 != null) $protein2 = $this->protein2;
                        
        echo 'Zadajte aspoň jeden element v interakcii. Na veľkých/malých písmenách nezáleží.';
        echo ' Všetky použité názvy proteínov sa nachádzajú <a href="pouzite_proteiny.php" target="_blank">TU</a> a slová pre interakciu <a href="pouzite_interakcie.php" target="_blank">TU</a>.';
        echo "<br>";
        
        echo 'Proteín 1: ';
        echo '<input type="text" name="protein1" value ="'.$protein1.'">';
        echo ' Napr. <i>ATM</i>';
        echo '<br/>'; 
        
        echo 'Slovo pre interakciu: ';
        echo '<input type="text" name="interactionWord" value ="'.$interakcia.'">';
        echo ' Napr. <i>regulator</i>';
        echo '<br/>';
        
        echo 'Proteín 2: ';
        echo '<input type="text" name="protein2" value ="'.$protein2.'">';
        echo ' Napr. <i>BRCA1</i>';
        echo '<br/>';       
        
        echo '<br/>'; 
        echo '<input type="submit" value = "Hľadať">';

        echo '</form>';        
        echo '</div>';

    
    }
    
    
}























































