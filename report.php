<?php

if(isset($_POST["type"])) {
    require './res/php/people.php';
    require './res/php/suite.php';
    require './res/php/booking.php';
    require './res/php/MySQL.php';
    require './res/php/bouncer.php';
    
    return getDetails();
} else if(isset($_POST["refresh"])) {
    require './res/php/bouncer.php';
    
    if(isset($_SESSION["record"])) {
        unset($_SESSION["record"]);
    }
    return;
}

require './res/php/bouncer.php';
require './res/html/head.html';
require './res/html/report.html';


require './res/html/footer.html';

function getDetails() {    
    
    if(!isset($_SESSION["record"]) || !isset($_SESSION["record"][$_POST["type"]])) {        
        $deets = ($_POST["type"] === "all" ? getAllLog() : getAllBookings());
        $_SESSION["record"][$_POST["type"]] = $deets;
    }
    
    $records = $_SESSION["record"][$_POST["type"]];    
    
    $tbl = "";
    foreach($records as $rcd) {
        
        $tbl .= '
        <tr>
            <td>'. $rcd->CLIENT->ns() .'</td>
            <td>'. $rcd->SUITE->NAME .'</td>
            <td>'. $rcd->STARTDATE .'</td>
            <td>'. $rcd->ENDDATE .'</td>
            <td>'. ($rcd->CANCELLED ? '<span style="color:orange;">Yes</span>' : '<span style="color:green;">No</span>') .'</td>
            <td>'. ($rcd->AGENT->ID === NULL ? "Self" : $rcd->AGENT->NAME ).'</td>
            <td>'. ($rcd->CHECKEDOUT === NULL ? ($rcd->CANCELLED ? '<span style="color:orange;">Cancelled</span>' : 'Still checked in') : $rcd->CHECKEDOUT ) .'</td>
        </tr>'; 
    }
        
    echo $tbl;
}

?>
