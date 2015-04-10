<?php
require './res/php/MySQL.php';
require './res/php/booking.php';
require './res/php/suite.php';
require './res/php/bouncer.php';

if(isset($_POST["delete"])) {    
    return delete();
} else if(isset($_POST["load"])) {
    $bookings = bookedSuitesBy($_POST["id"]);
    return displayBookingsA($bookings);
}

require './res/html/head.html';

if(!isset($_SESSION["userID"])) {
    echo '
        <div class="jumbotron">
          <h1 style="text-align:center;">Login or register to cancel a room</h1>          
        </div>';
} else {
    if($_SESSION["userType"] === "client") {
        displayBookings();
    } else {
        require './res/php/people.php';
        
        $clients = allClients();
        require './res/html/cancelA.html';   
    }
}

require './res/html/footer.html';

function displayBookings() {    
    
    $bookings = bookedSuitesBy($_SESSION["userID"]);
    
    if(count($bookings) == 0) {
        echo '
        <div class="jumbotron">
            <h1 style="text-align:center">Cancellation</h1>
            <p style="text-align:center;">You do not have any reservations to cancel.</p>
        </div>';
    } else {
        require './res/html/cancel.html';
        $_SESSION["bookings"] = $bookings;
    }
}

function delete() {
    $booking = $_SESSION["bookings"][$_POST["id"]];
    
    $result = deleteBook($booking, TRUE);    
    
    if($result["result"]) {
        echo $_POST["id"];
    } else {
        echo "ERROR " . $result["erro"];   
    }
}

function displayBookingsA($bookings) {
    if(count($bookings) == 0) {
        echo '
            <tr">
                <td>-1</td>
                <td>NO</td>
                <td>BOOKED</td>
                <td>SUITES</td>
                <td><button class="btn btn-default" disabled>Cancel</button></td>
            </tr>';
    } else {
        $ret = "";
        
        foreach($bookings as $bkn) {            
            $ret .= '
            <tr id="'.$bkn->BOOKID.'">
                <td>'. $bkn->ID .'</td>
                <td>'. $bkn->SUITE->NAME .'</td>
                <td>'. $bkn->STARTDATE .'</td>
                <td>'. $bkn->ENDDATE .'</td>
                <td><button class="btn btn-warning" onclick="deleteBook('.$bkn->BOOKID.')" id="btn'.$bkn->BOOKID.'">Cancel</button></td>
            </tr>'; 
        }
        
        $_SESSION["bookings"] = $bookings;
        echo $ret;
    }
}

?>
