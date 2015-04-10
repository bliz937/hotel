<?php
require './res/php/bouncer.php';
require './res/html/head.html';

if(!isset($_POST["suite"])) {
    oldHtml();
}
else if(isset($_POST["client"])) {
    book($_POST["client"]);   
} else {
    book($_SESSION["userID"]);
}

require './res/html/footer.html';

function book($userID) {
    require './res/php/MySQL.php';
    require './res/php/booking.php';
    
    $booking = new booking($userID, $_POST["suite"], $_POST["fromDate"], $_POST["toDate"]);
    
    if($_SESSION["userID"] === $userID) {
        $booked = bookC($booking);
    } else {
        $booked = bookC($booking, $_SESSION["userID"]);
    }
    
    if($booked["result"]) {
        require './res/html/bookCsuc.html';
    } else {
        echo '
            <div class="jumbotron">
              <h1 style="text-align:center;">Error booking</h1>
              <p style="text-align:center;color:orange;">'.$booked["error"].'</p>
            </div>';   
    }
}

function oldHtml() {
    if(!isset($_SESSION["userType"])) {
        echo '<div class="jumbotron" style="text-align:center;"><h1>Login or register to book a room</h1></div>';
        return;
    }

    $userType = $_SESSION["userType"];

    require './res/php/MySQL.php';
    require './res/php/suite.php';

    if($userType === "client") {
        userBook();
    } else if($userType === "agent") {
        agentBook();
    } else {
        echo '
            <div class="jumbotron"><h1>Unrecognized user type</h1></div>';
    }
}

function userBook() {
    $suites = availableSuites();
    require './res/html/book.html';
}

function agentBook() {
    require './res/php/people.php';
    $suites = availableSuites();
    $clients = allClients();
    require './res/html/book.html';    
}

function selectOpts($clients) {
    $ret = "";

    foreach($clients as $cln) {
        $ret .= '<option value="'.$cln->ID.'" id="'.$cln->ID.'">'.$cln->ns().'</option>';
    }

    echo $ret;
}

?>
