<?php
class booking {
    public $ID; //ID from log table
    public $BOOKID; //ID from booking table
    public $CLIENT;
    public $CLIENTID;
    public $AGENT;
    public $AGENTID;
    public $SUITE;
    public $SUITEID;
    public $STARTDATE;
    public $ENDDATE;
    public $DAYS;
    public $COST;
    public $CANCELLED;
    public $CHECKEDOUT;
    
    function __construct($clientID,$suiteID,$startDate,$endDate) {
        $this->CLIENTID = $clientID;
        $this->SUITEID = $suiteID;
        $this->STARTDATE = $startDate;
        $this->ENDDATE = $endDate;
    }
        
}
?>