<?php
include 'session.php';
class event {
    private $db;
     
    public function __construct(database $db) 
    {
      $this->db = $db;
    }
    public function create($Uid,$eventName, $description, $DateandTime,$Location) {
        
        $sql = "INSERT INTO eventstable (id, eventName, description, DateandTime, Location) 
                VALUES (:Uid, :eventName, :description, :DateandTime, :Location)";
        $bind = [
        'Uid' => $Uid,
        'eventName' => $eventName,
        'description' => $description,
        'DateandTime' => $DateandTime,
        'Location' => $Location
    ];
        if ($this->db->query($sql,$bind)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function read($Uid) {
        // Query to fetch user data based on id
        $sql = "SELECT * FROM eventstable WHERE id = :Uid";
        $bind = ['Uid'=> $Uid];
        $result = $this->db->query($sql,$bind);
        return $result;
    }
     public function edit($Eid,$eventName, $description, $DateandTime,$Location) {
         //mitigate sql injection
         $sql = "UPDATE eventstable SET
            eventName = :eventName, 
            description = :description, 
            DateandTime = :DateandTime, 
            Location = :Location 
            WHERE eventId = :Eid";
        $bind = [
        'eventName' => $eventName,
        'description' => $description,
        'DateandTime' => $DateandTime,
        'Location' => $Location,
        'Eid' => $Eid
    ];
         if ( $this->db->query($sql,$bind)) {
            header('Location: events.php');
            exit;
            } else {
                echo "Error edting record: " . $this->db->error;
                }       
    }
    
    public function delete($Eid) {
        //Query to locate event on event id
        $sql = "DELETE FROM eventstable WHERE eventId = :Eid";
        $bind = ['Eid'=> $Eid];
        if ( $this->db->query($sql, $bind ) ) {
            header('Location: events.php');
            exit;
            } else {
                echo "Error deleting record: " . $this->db->error;
                }
    }
    
    public function getAllEvents() {
        $sql = "SELECT * FROM eventstable";
        $events = $this->db->query($sql);
        return $events;   
    }
}
