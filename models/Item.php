<?php
/**
 * Item Class
 * Represents a item with methods to manipulate it. To be used in
 * the Lists class.
**/ 

include_once __DIR__ . "/../common/helpers.php";

class Item {
    private $itemID;
    private $files;
    private $db;
        
    // Constructor CREATES a new item for a itemID
    public function __construct($itemID = null, $listID = null, $name = null, $due = null, $longitude = null, $latitude = null) {
    	if ($listID != null) {
    	$this->db = new Database();
    	// Create list item
            $stmt = $this->db->prepare("INSERT INTO items VALUES (NULL, :listID, :item, :due, 0, :longitude, :latitude, 0)");
            $stmt->bindValue(":listID", $listID, SQLITE3_INTEGER);
            $stmt->bindValue(":name", $name, SQLITE3_TEXT);
            $stmt->bindValue(":due", $due, SQLITE3_DATETIME);
            $stmt->bindValue(":longitude", $longitude, SQLITE3_TEXT);
            $stmt->bindValue(":latitude", $latitude, SQLITE3_TEXT);
            $stmt->execute();
            // Grab created item ID
            $itemID = $this->db->lastRowID();
            $this->itemID = $itemID;
    	} else {
    	    $this->db = new Database();
            $this->itemID = $itemID;    
    	}
    }
    
    // Returns item ID
    public function getItemID() {
        return $this->itemID;
    }
    
    // Deletes the item anything linked to it
    public function delete() {
        // Delete from files
        $stmt = $this->db->prepare("DELETE FROM files where itemID = :itemID");
        $stmt->bindValue(":itemID", $this->getItemID(), SQLITE3_INTEGER);
        $stmt->execute();
        // Delete item
        $stmt = $this->db->prepare("DELETE FROM items WHERE item = :itemID");
        $stmt->bindValue(":itemID", $this->getItemID(), SQLITE3_INTEGER);
        $stmt->execute();
        // Clear variables here
        unset($this->itemID);
        unset($this->files);
    }
    
    // Sets the properties of the item
    public function setItem($name, $due, $longitude, $latitude, $urgent, $completed) {
        $stmt = $this->db->prepare("UPDATE items SET name = :name, due = :due, urgent = :urgent, longitude = :longitude, latitude = :latitude, completed = :completed WHERE id = :itemID");
        $stmt->bindValue(":itemID", $this->getItemID(), SQLITE3_INTEGER);
        $stmt->bindValue(":name", $name, SQLITE3_TEXT);
        $stmt->bindValue(":due", $due, SQLITE3_DATETIME);
        $stmt->bindValue(":longitude", $longitude, SQLITE3_TEXT);
        $stmt->bindValue(":latitude", $latitude, SQLITE3_TEXT);
        $stmt->bindValue(":completed", $completed, SQLITE3_INTEGER);
        $stmt->bindValue(":urgent", $urgent, SQLITE3_INTEGER);
        $stmt->execute();
    }
    
    // Returns an assosciate array containing properties
    public function getItem() {
        $stmt = $this->db->prepare("SELECT * FROM items WHERE id = :itemID");
        $stmt->bindValue(":itemID", $this->getItemID(), SQLITE3_INTEGER);
        $results = $stmt->execute();
        // Get one row
        $row = $results->fetchArray();
        return $row;
    }
    
}

?>