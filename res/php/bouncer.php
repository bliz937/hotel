<?php
session_start();

function navbar() {
    $LOGGEDIN = FALSE;
    $ech = "";
    
    if(isset($_SESSION['userID'])){
        $LOGGEDIN = TRUE;
        
        if($_SESSION["userType"] === "client") {
            $ech .= clientNB();
        } else if($_SESSION["userType"] === "agent") {
            $ech .= agentNB();   
        }
    } 
    
    $ech .= '</ul><ul class="nav navbar-nav navbar-right">';
    
    if($LOGGEDIN) {
        $ech .= '<li><a href="#">Welcome ' . $_SESSION["userName"] . '</a></li>';
        $ech .= '<li>                    
                        <a href="#" onclick="logout()">Logout</a>                        
                </li>    
                ';
    } else {
        $ech .= '<li><a href="#loginModal" data-toggle="modal">Login</a></li>
                <li><a href="http://172.16.38.44/~shev/hotel/register.php">Register</a></li>';   
    }
    
    $ech .= '</ul>';
    
    echo $ech;
}

function clientNB() {
    return '
        <li>
            <a href="book.php">Book</a>
        </li>
        
        <li>
            <a href="cancel.php">Cancel</a>
        </li>
        
        <li>
            <a href="checkout.php">Checkout</a>
        </li>
        
        <li>
            <a href="account.php">Account</a>
        </li>
    ';
}

function agentNB() {
    return '
        <li>
            <a href="book.php">Book</a>
        </li>
        
        <li>
            <a href="cancel.php">Cancel</a>
        </li>
        
        <li>
            <a href="checkout.php">Checkout</a>
        </li>
        
        <li>
            <a href="report.php">Report</a>
        </li>
        
        <li>
            <a href="account.php">Accounts</a>
        </li>
    ';
}

?>