<?php
class person {
    public $ID;
    public $NAME;
    public $SURNAME;
    public $EMAIL;
    public $PASSWORD;
    public $TYPE;
    public $BALANCE;
    
    function __construct($id,$name,$surname,$type,$balance) {
        $this->ID = $id;
        $this->NAME = $name;
        $this->SURNAME = $surname;
        $this->TYPE = $type;
        $this->BALANCE = $balance;
    }
    
    //returns the name and surname of the person
    function nameAndSurname() {
        return $this->NAME . ' ' . $this->SURNAME;
    }
    
    function updateSession() {
        $_SESSION["userID"] = $this->ID;
        $_SESSION["userName"] = $this->NAME;
        $_SESSION["userSurname"] = $this->SURNAME;
        $_SESSION["userEmail"] = $this->EMAIL;
        $_SESSION["userType"] = $this->TYPE;
        $_SESSION["userBalance"] = $this->BALANCE;   
    }
    
    function ns() {
        return $this->NAME . ' ' . $this->SURNAME;     
    }
}
?>