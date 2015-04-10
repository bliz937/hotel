<?php
require './res/php/bouncer.php';

if(isset($_POST["rl"])) {
    require './res/php/MySQL.php';
    require './res/php/people.php';    
    
    if($_POST["rl"] === "r") {
        registerUser();
    } else if($_POST["rl"] === "l") {
        loginUser();
    } else if($_POST["rl"] === "o") {
        session_unset();
        session_destroy();
        require './res/html/head.html';
        require './res/html/logout.html';
    } else {
        require './res/html/head.html';
        require './res/html/reglogerror.html';   
    }
} else {
    require './res/html/head.html';
    require './res/html/register.html';
}


require './res/html/footer.html';

function registerUser($userType = "client") {    
    $person = new person(-1, $_POST["FName"], $_POST["LName"], "client", 0);
    $person->EMAIL = $_POST["Email"];
    $person->PASSWORD = md5($_POST["Password"]);
    $person->TYPE = $userType;
    $result = register($person);
    
    if($result["result"]) {
       registrationSuccess();
    } else {
        registrationError($result["error"]);
    }
}

function registrationSuccess() {
    $person = getByEmail($_POST["Email"]);
    $person->updateSession();
    require './res/html/head.html';
    require './res/html/reglanding.html';
}

function loginUser() {
    $person = login($_POST["Email"], md5($_POST["Password"]));
    
    if($person === NULL) {
        require './res/html/head.html';
        require './res/html/loginfail.html';   
    } else {                
        $person->updateSession();        
        require './res/html/head.html';
        require './res/html/loginsuc.html';        
    }
}

function registrationError($error) {
    require './res/html/head.html';
    echo '<h1>' . $error . '</h1>';
}

?>
