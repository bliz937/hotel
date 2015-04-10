<?php
class suite {
    public $ID;
    public $NAME;
    public $AVAILABLE;
    public $COSTPERN;
    public $OPEN;
    
    function __construct($id,$name,$available,$cost) {
        $this->ID = $id;
        $this->NAME = $name;
        $this->AVAILABLE = $available;
        $this->COSTPERN = $cost;        
    }
    
}
?>