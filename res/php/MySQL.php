<?php
/**
 * These are the database login details
 */  
define("HOST", "localhost");
define("USER", "shev");
define("PASS", "shevpass");
define("DATABASE", "shev_hotel");
//define("CAN_REGISTER", "any");
//define("DEFAULT_ROLE", "member");
//define("SECURE", FALSE);    // FOR DEVELOPMENT ONLY!!!!

//Creates the connection string and returns it
function ConString()
{
    $con;
    
    try
    {        
        $con = mysqli_connect(HOST,USER,PASS,DATABASE);
    }
    catch(Exception $e)
    {
        echo "MySQL exception!" . $e;
        return;   
    }
    
    if (mysqli_connect_errno()) {
        echo "<h1>Failed to connect to MySQL: <h1>" . mysqli_connect_error();
        return;
    }
    
    return $con;
}

//Registers a new person
function register($person)
{
    $con = ConString();
    
    $sql = "INSERT INTO people (ppl_LName, ppl_FName, ppl_Email, ppl_Password, ppl_Type) 
            VALUES ('".$person->SURNAME."', '".$person->NAME."', '".$person->EMAIL."', '".$person->PASSWORD."', '".$person->TYPE."');";

    $ret = false;
    $arr = array();
    
    if ($con->query($sql)) {
        $ret = true;
    } else {
        $arr["error"] = $con->error;
    }
    
    $arr["result"] = $ret;
    $con->close();
    
    return $arr;
}

function getByEmail($email)
{
    $con = ConString();
    
    $sql = "SELECT ppl_ID,ppl_FName,ppl_LName,ppl_Type,ppl_Balance,ppl_Email FROM people 
            WHERE ppl_Email = '".$email."';";

    $result = $con->query($sql);
    $person = NULL;
    
    if($row = $result->fetch_assoc()) {
        $person = new person($row["ppl_ID"], $row["ppl_FName"], $row["ppl_LName"], $row["ppl_Type"], $row["ppl_Balance"]);
        $person->EMAIL = $row["ppl_Email"];
    }
    
    $con->close();
    
    return $person;
}

function login($email, $password) {
    $con = ConString();
    
    $sql = "SELECT ppl_ID,ppl_FName,ppl_LName,ppl_Type,ppl_Balance,ppl_Email FROM people 
            WHERE ppl_Email = '".$email."' AND ppl_Password = '".$password."';";

    $result = $con->query($sql);
    $person = NULL;
    
    if($row = $result->fetch_assoc()) {
        $person = new person($row["ppl_ID"], $row["ppl_FName"], $row["ppl_LName"], $row["ppl_Type"], $row["ppl_Balance"]);
        $person->EMAIL = $row["ppl_Email"];
    }
    
    $con->close();
    
    return $person;
}

function availableSuites() {
    $con = ConString();
    $sql = "SELECT suite.sui_ID, suite.sui_Name, suite.sui_CPN, suite.sui_Available, IFNULL(tmpopen, suite.sui_Available) AS open FROM suite 
            LEFT JOIN 
                (SELECT booking.sui_ID, sui_Name, sui_CPN, sui_Available, (sui_Available - COUNT(booking.sui_ID)) AS tmpopen FROM booking 
                LEFT JOIN suite ON suite.sui_ID = booking.sui_ID  
                WHERE CURDATE() BETWEEN boo_StartDate AND boo_EndDate
                GROUP BY booking.sui_ID) AS tmp 
            ON tmp.sui_ID = suite.sui_ID;";
    
    $result = $con->query($sql);
    $ret = array();
    
    while($row = $result->fetch_assoc()) {
        $suite = new suite($row["sui_ID"], $row["sui_Name"], $row["sui_Available"], $row["sui_CPN"]);
        $suite->OPEN = $row["open"];
        $ret[$row["sui_Name"]] = $suite;
    }
    
    $con->close();
    return $ret;
}

function availableSuitesByDate($from,$to) {
    $con = ConString();
    $sql = "SELECT suite.sui_ID, suite.sui_Name, suite.sui_CPN, suite.sui_Available, IFNULL(tmpopen, suite.sui_Available) AS open FROM suite 
            LEFT JOIN 
                (SELECT booking.sui_ID, sui_Name, sui_CPN, sui_Available, (sui_Available - COUNT(booking.sui_ID)) AS tmpopen FROM booking 
                LEFT JOIN suite ON suite.sui_ID = booking.sui_ID  
                WHERE ('".$from."' BETWEEN boo_StartDate AND boo_EndDate)
                OR ('".$to."' BETWEEN boo_StartDate AND boo_EndDate)
                OR (boo_StartDate BETWEEN '".$from."' AND '".$to."')
                OR (boo_EndDate BETWEEN '".$from."' AND '".$to."')
                GROUP BY booking.sui_ID) AS tmp 
            ON tmp.sui_ID = suite.sui_ID;;";
    
    $result = $con->query($sql);
    $ret = array();
    
    while($row = $result->fetch_assoc()) {
        $suite = new suite($row["sui_ID"], $row["sui_Name"], $row["sui_Available"], $row["sui_CPN"]);
        $suite->OPEN = $row["open"];
        $ret[$row["sui_Name"]] = $suite;
    }
    
    $con->close();
    return $ret;
}

//book a room
function bookC($booking, $agentID = -1)
{
    $con = ConString();
    
    if($agentID === -1) {
        $sql = "INSERT INTO booking (boo_StartDate, boo_EndDate, sui_ID, ppl_ID) 
                VALUES ('".$booking->STARTDATE."', '".$booking->ENDDATE."', '".$booking->SUITEID."', '".$booking->CLIENTID."');";
    } else {
        $sql = "INSERT INTO booking (boo_StartDate, boo_EndDate, sui_ID, ppl_ID, ppl_IDAgent) 
                VALUES ('".$booking->STARTDATE."', '".$booking->ENDDATE."', '".$booking->SUITEID."', '".$booking->CLIENTID."', '".$agentID."');";
    }    
    
    $ret = false;
    $arr = array();
    
    if ($con->query($sql)) {
        
        if($agentID === -1) {
            $sql = "INSERT INTO log (log_StartDate, log_EndDate, sui_ID, ppl_ID, log_Cancelled) 
                    VALUES ('".$booking->STARTDATE."', '".$booking->ENDDATE."', '".$booking->SUITEID."', '".$booking->CLIENTID."', '0');";
        } else {
            $sql = "INSERT INTO log (log_StartDate, log_EndDate, sui_ID, ppl_ID, log_Cancelled, ppl_IDAgent) 
                    VALUES ('".$booking->STARTDATE."', '".$booking->ENDDATE."', '".$booking->SUITEID."', '".$booking->CLIENTID."', '0', '".$agentID."');";
        }
        
        if ($con->query($sql)) {
            $ret = true;
        } else {
            $arr["error"] = $con->error;   
        }
        
    } else {
        $arr["error"] = $con->error;
    }
    
    $arr["result"] = $ret;
    $con->close();
    
    return $arr;
}

function latestBooking($clientID, $suiteID) {
    $con = ConString();
    
    $sql = "SELECT MAX(log_ID) AS ID, log_StartDate, log_EndDate, log.sui_ID, log.ppl_ID, ppl_LName, ppl_FName, sui_Name, 
            (log_EndDate - log_StartDate) AS days, sui_CPN, ppl_Type, ppl_Balance, sui_Available FROM log 
            LEFT JOIN people ON people.ppl_ID = log.ppl_ID 
            LEFT JOIN suite ON suite.sui_ID = log.sui_ID 
            WHERE log.sui_ID = ".$suiteID." AND log.ppl_ID = ".$clientID.";";

    $result = $con->query($sql);
    $booking = NULL;
    
    if($row = $result->fetch_assoc()) {
        $person = new person($row["ppl_ID"], $row["ppl_FName"], $row["ppl_LName"], $row["ppl_Type"], $row["ppl_Balance"]);
        $suite = new suite($row["sui_ID"], $row["sui_Name"], $row["sui_Available"], $row["sui_CPN"]);
        $booking = new booking($row["ppl_ID"],$row["sui_ID"],$row["log_StartDate"],$row["log_EndDate"]);
        $booking->CLIENT = $person;
        $booking->SUITE = $suite;
        $booking->ID = $row["ID"];
        $booking->DAYS = $row["days"];
        $booking->COST = $booking->DAYS * $row["sui_CPN"];
    }
    
    $con->close();
    
    return $booking;
}

function bookedSuitesBy($userID) {
    $con = ConString();
    $sql = "SELECT boo_ID,log_ID,boo_StartDate,boo_EndDate,booking.sui_ID,sui_Name,sui_CPN FROM booking 
            LEFT JOIN suite ON booking.sui_ID = suite.sui_ID
            LEFT JOIN log ON booking.ppl_ID = log.ppl_ID 
            AND booking.boo_StartDate = log.log_StartDate 
            AND booking.boo_EndDate = log.log_EndDate
            AND booking.sui_ID = log.sui_ID
            WHERE log_Cancelled = 0
            AND booking.ppl_ID = ".$userID.";";
    
    $result = $con->query($sql);
    $bookings = array();
    
    while($row = $result->fetch_assoc()) {
        $suite = new suite($row["sui_ID"], $row["sui_Name"], "", $row["sui_CPN"]);
        $booking = new booking($userID,$row["sui_ID"],$row["boo_StartDate"],$row["boo_EndDate"]);
        $booking->SUITE = $suite;
        $booking->BOOKID = $row["boo_ID"];
        $booking->ID = $row["log_ID"];
        $bookings[$row["boo_ID"]] = $booking;        
    }
    
    $con->close();
    return $bookings;
}

function logOf($userID) {
    $con = ConString();
    $sql = "SELECT log_ID,log_StartDate,log_EndDate,log.sui_ID,sui_Name,sui_CPN,people.ppl_ID,ppl_FName,ppl_LName,log_Cancelled,log_CheckedOut FROM log 
            LEFT JOIN suite ON suite.sui_ID = log.sui_ID 
            LEFT JOIN people ON log.ppl_IDAgent = people.ppl_ID 
            WHERE log.ppl_ID = ".$userID.";";
    
    $result = $con->query($sql);
    $bookings = array();
    
    while($row = $result->fetch_assoc()) {
        
        $agent = new person($row["ppl_ID"],$row["ppl_FName"],$row["ppl_LName"], "agent", 0);
        $suite = new suite($row["sui_ID"], $row["sui_Name"], "", $row["sui_CPN"]);
        $booking = new booking($userID,$row["sui_ID"],$row["log_StartDate"],$row["log_EndDate"]);
        $booking->SUITE = $suite;        
        $booking->ID = $row["log_ID"];
        $booking->AGENTID = $row["ppl_ID"];
        $booking->AGENT = $agent;
        $booking->CANCELLED = ($row["log_Cancelled"] === "1" ? TRUE : FALSE);
        $booking->CHECKEDOUT = $row["log_CheckedOut"];
        $bookings[] = $booking;        
    }
    
    $con->close();
    return $bookings;
}

//Deletes a booking and updates the log accordingly
function deleteBook($booking, $cancellation) {
    $con = ConString();
    
    $sql = "DELETE FROM booking
            WHERE boo_ID = ". $booking->BOOKID .";";
    
    $ret = false;
    $arr = array();
    
    if ($con->query($sql)) {
        $ret = true;
        
        if($cancellation) {
            $sql = "UPDATE log SET log_Cancelled = '1' 
                WHERE sui_ID = '".$booking->SUITE->ID."'
                AND ppl_ID = '".$booking->CLIENTID."'
                AND log_StartDate = '".$booking->STARTDATE."'
                AND log_EndDate = '".$booking->ENDDATE."';";
            
            if (!$con->query($sql)) {
                $arr["error"] = $con->error;
            }
        } else {
             $sql = "UPDATE log SET log_CheckedOut = '".date('Y-m-d H:i:s')."' 
                WHERE sui_ID = '".$booking->SUITE->ID."'
                AND ppl_ID = '".$booking->CLIENTID."'
                AND log_StartDate = '".$booking->STARTDATE."'
                AND log_EndDate = '".$booking->ENDDATE."';";
            
            if (!$con->query($sql)) {
                $arr["error"] = $con->error;
            }              
        }
    } else {
        $arr["error"] = $con->error;
    }
    
    $arr["result"] = $ret;
    $con->close();
    
    return $arr;
}

function allClients() {
    $con = ConString();
    $sql = "SELECT ppl_ID,ppl_FName,ppl_LName,ppl_Balance FROM people 
            WHERE ppl_Type = 'client';";
    
    $result = $con->query($sql);
    $clients = array();
    
    while($row = $result->fetch_assoc()) {
        $client = new person($row["ppl_ID"],$row["ppl_FName"],$row["ppl_LName"],"client", $row["ppl_Balance"]);
        $clients[] = $client;
    }
    
    $con->close();
    return $clients;
}

function getAllLog() {
    $con = ConString();
    $sql = "SELECT log_ID,log_StartDate,log_EndDate,log.sui_ID,sui_Name,sui_CPN,agent.ppl_ID AS age_ID,agent.ppl_FName AS age_FName,agent.ppl_LName AS age_LName,log_Cancelled,log_CheckedOut, people.ppl_ID,people.ppl_FName,people.ppl_LName,people.ppl_Balance FROM log 
            LEFT JOIN suite ON suite.sui_ID = log.sui_ID 
            LEFT JOIN people AS agent ON log.ppl_IDAgent = agent.ppl_ID
            LEFT JOIN people ON log.ppl_ID = people.ppl_ID
            ORDER BY log_ID ASC;";
    
    $result = $con->query($sql);
    $bookings = array();
    
    while($row = $result->fetch_assoc()) {
        
        $agent = new person($row["age_ID"],$row["age_FName"],$row["age_LName"], "agent", 0);
        $client = new person($row["ppl_ID"],$row["ppl_FName"],$row["ppl_LName"], "client", $row["ppl_Balance"]);
        $suite = new suite($row["sui_ID"], $row["sui_Name"], "", $row["sui_CPN"]);
        $booking = new booking($row["ppl_ID"],$row["sui_ID"],$row["log_StartDate"],$row["log_EndDate"]);
        $booking->SUITE = $suite;        
        $booking->ID = $row["log_ID"];
        $booking->AGENTID = $row["age_ID"];
        $booking->AGENT = $agent;
        $booking->CLIENTID = $row["ppl_ID"];
        $booking->CLIENT = $client;
        $booking->CANCELLED = ($row["log_Cancelled"] === "1" ? TRUE : FALSE);
        $booking->CHECKEDOUT = $row["log_CheckedOut"];
        $bookings[] = $booking;        
    }
    
    $con->close();
    return $bookings;
}

function getAllBookings() {
    $con = ConString();
    $sql = "SELECT boo_ID,boo_StartDate,boo_EndDate,booking.sui_ID,sui_Name,sui_CPN,agent.ppl_ID AS age_ID,agent.ppl_FName AS age_FName,agent.ppl_LName AS age_LName, people.ppl_ID,people.ppl_FName,people.ppl_LName,people.ppl_Balance FROM booking 
            LEFT JOIN suite ON suite.sui_ID = booking.sui_ID 
            LEFT JOIN people AS agent ON booking.ppl_IDAgent = agent.ppl_ID
            LEFT JOIN people ON booking.ppl_ID = people.ppl_ID
            ORDER BY boo_ID ASC;";
    
    $result = $con->query($sql);
    $bookings = array();
    
    while($row = $result->fetch_assoc()) {
        
        $agent = new person($row["age_ID"],$row["age_FName"],$row["age_LName"], "agent", 0);
        $client = new person($row["ppl_ID"],$row["ppl_FName"],$row["ppl_LName"], "client", $row["ppl_Balance"]);
        $suite = new suite($row["sui_ID"], $row["sui_Name"], "", $row["sui_CPN"]);
        $booking = new booking($row["ppl_ID"],$row["sui_ID"],$row["boo_StartDate"],$row["boo_EndDate"]);
        $booking->SUITE = $suite;        
        $booking->BOOKID = $row["boo_ID"];
        $booking->AGENTID = $row["age_ID"];
        $booking->AGENT = $agent;
        $booking->CLIENTID = $row["ppl_ID"];
        $booking->CLIENT = $client;
        $bookings[] = $booking;        
    }
    
    $con->close();
    return $bookings;
}

?>
