<?php
require './res/php/bouncer.php';

if(isset($_POST["id"])) {
        return clientDetails();   
}

require './res/html/head.html';

if(!isset($_SESSION["userID"])) {
    echo '
        <div class="jumbotron">
          <h1 style="text-align:center;">Login or register to view your account history</h1>          
        </div>';
} else {
    getHistory();
}


require './res/html/footer.html';

function getHistory() {
    require './res/php/people.php';
    require './res/php/suite.php';
    require './res/php/booking.php';
    require './res/php/MySQL.php';    
    
    $bookings = logOf($_SESSION["userID"]);
    
    if($_SESSION["userType"] === "client") {        
        require './res/html/account.html';
    } else {
        $clients = allClients();
        require './res/html/accountA.html';   
    }
}

function clientDetails() {
    require './res/php/people.php';
    require './res/php/suite.php';
    require './res/php/booking.php';
    require './res/php/MySQL.php'; 
    
    $bookings = logOf($_POST["id"]);
    $ech = "";

    foreach($bookings as $bkn) {

        $ech .= '
        <tr>
            <td>'. $bkn->ID .'</td>
            <td>'. $bkn->SUITE->NAME .'</td>
            <td>'. $bkn->STARTDATE .'</td>
            <td>'. $bkn->ENDDATE .'</td>
            <td>'. ($bkn->CANCELLED ? '<span style="color:orange;">Yes</span>' : '<span style="color:green;">No</span>') .'</td>
            <td>'. ($bkn->AGENT->ID === NULL ? "Self" : $bkn->AGENT->NAME ).'</td>
            <td>'. ($bkn->CHECKEDOUT === NULL ? ($bkn->CANCELLED ? '<span style="color:orange;">Cancelled</span>' : 'Still checked in') : $bkn->CHECKEDOUT ) .'</td>
        </tr>';                
        }
    
    echo $ech;
}

?>
