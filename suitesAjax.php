<?php
require './res/php/suite.php';
require './res/php/MySQL.php';


if(isset($_POST["from"]) && isset($_POST["to"])) {
    $suites = availableSuitesByDate($_POST["from"],$_POST["to"]);    
    $ech = "[ ";
    
    foreach($suites as $sui) {
        $ech .= '{ "name" : "' . $sui->NAME . '", ';
        $ech .= '"open" : ' . $sui->OPEN . ' }, ';
    }
    
    $ech = substr($ech,0,-2);
    
    $ech .= " ]";
    
    echo $ech;
}


?>
