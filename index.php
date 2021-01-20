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
<?php 
require_once 'Vysledky.php';
$vys = new Vysledky();
$vys->vykresliVyhladavaciFormular();
$vys->vykresliVysledky();

?>
	</body>
</html>



























































