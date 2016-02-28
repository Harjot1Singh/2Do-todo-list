<?php
/**
 * List Class
 * Represents a single list with methods to manipulate it. To be used in
 * the User class.
**/ 

include_once __DIR__ . "../common/helpers.php";


class TodoList {
    private $db;
    private $listID;
    private $items;
    private $users;
        
    // Constructor CREATES a new list for a userID
    public function __construct($userID = null, $name = null, $listID = null) {
        if ($name) {
    	    $this->db = new Database();
    	    // Create list item
            $stmt = $this->db->prepare("INSERT INTO lists VALUES (NULL, :name)");
            $stmt->bindValue(":name", $name, SQLITE3_TEXT);
            $stmt->execute();
            // Grab created list ID
            $listID = $this->db->lastRowID();
            // Now link it to the given userID
            $stmt = $this->db->prepare("INSERT INTO userlists VALUES (:userID, :listID)");
            $stmt->bindValue(":userID", $userID, SQLITE3_INTEGER);
            $stmt->bindValue(":listID", $listID, SQLITE3_INTEGER);
            $stmt->execute();
            $this->listID = $listID;
        } else { // Assumes listID was given
            $this->db = new Database();
            $this->listID = $listID;
        }
    }

    // Returns list ID
    public function getListID() {
        return $this->listID;
    }
    
    // Deletes the list and anyone linked to it
    public function delete() {
        // Delete all users linked to list
        $stmt = $this->db->prepare("DELETE FROM userlists WHERE listID = :listID");
        $stmt->bindValue(":listID", $this->getListID(), SQLITE3_INTEGER);
        $stmt->execute();
        // Delete all items linked to list
        $stmt = $this->db->prepare("DELETE FROM items WHERE listID = :listID");
        $stmt->bindValue(":listID", $this->getListID(), SQLITE3_INTEGER);
        $stmt->execute();
        // Delete from list table
        $stmt = $this->db->prepare("DELETE FROM lists where id = :listID");
        $stmt->bindValue(":listID", $this->getListID(), SQLITE3_INTEGER);
        $stmt->execute();
        // Clear variables here
        unset($this->listID);
        unset($this->users);
        unset($this->items);
    }
    
    // Sets the name of the list
    public function setName($name) {
        $stmt = $this->db->prepare("UPDATE lists SET name = :name WHERE id = :listID");
        $stmt->bindValue(":name", $name, SQLITE3_TEXT);
        $stmt->bindValue(":listID", $this->getListID(), SQLITE3_INTEGER);
        $stmt->execute();
    }
    
    // Gets name of the list
    public function getName() {
        // Get name
        $stmt = $this->db->prepare("SELECT name FROM lists WHERE id = :listID");
        $stmt->bindValue(":listID", $this->getListID(), SQLITE3_INTEGER);
        $results = $stmt->execute();
        $row = $results->fetchArray();
        $name = $row["name"];
        return $name;
    }
    
    // Gets all the users for list
    public function getUsers() {
        if (isset($this->users))
            return $this->users;
        // Get name, userID on this list
        $stmt = $this->db->prepare("SELECT users.name, userlists.userID FROM userlists, users WHERE userlists.userID = users.id AND userlists.listID = :listID");
        $stmt->bindValue(":listID", $this->getListID(), SQLITE3_INTEGER);
        $stmt->execute();
        // "Cache" each user
        while ($row = $results->fetchArray()) {;
            $this->users[$row["userID"]] = $row["name"];
        }
        return $this->users;
    }
    
    // Add user to list
    public function addUser($userID) {
        $stmt = $this->db->prepare("INSERT INTO userlists VALUES (:userID, :listID)");
        $stmt->bindValue(":userID", $userID, SQLITE3_INTEGER);
        $stmt->bindValue(":listID", $this->getListID(), SQLITE3_INTEGER);
        $stmt->execute();
        // Add the user ID to the "cache"
        $stmt = $this->db->prepare("SELECT name FROM users WHERE id = :userID");
        $stmt->bindValue(":userID", $userID, SQLITE3_INTEGER);
        $results = $stmt->execute();
        // Get first now and cache name from it
        $row = $results->fetchArray();
        $this->users[$userID] = $row["name"];
    }
    
    // Add user to list
    public function removeUser($userID) {
        $stmt = $this->db->prepare("DELETE FROM userlists WHERE userID = :userID AND listID = :listID");
        $stmt->bindValue(":userID", $userID, SQLITE3_INTEGER);
        $stmt->bindValue(":listID", $this->getListID(), SQLITE3_INTEGER);
        $stmt->execute();
        // Remove from "cache"
        unset($this->users[$userID]);
    }
 
    // Gets not completed items on list
    public function getDueItems() {
        // if (isset($this->items))
            // return $this->items;
        // Get name, userID on this list with order priority
        $stmt = $this->db->prepare("SELECT id FROM items WHERE listID = :listID AND completed = 0 ORDER BY urgent DESC, id DESC");
        $stmt->bindValue(":listID", $this->getListID(), SQLITE3_INTEGER);
        $results = $stmt->execute();
        // "Cache" each user
        $this->items = array();
        while ($row = $results->fetchArray()) {
        // Inefficient but will do
            $this->items[] = new Item($row["id"]);
        }
        return $this->items;
    }
 
     // Gets completed items on list
    public function getCompletedItems() {
        // if (isset($this->items))
            // return $this->items;
        // Get name, userID on this list with order priority
        $stmt = $this->db->prepare("SELECT id FROM items WHERE listID = :listID AND completed = 1 ORDER BY urgent DESC, id DESC");
        $stmt->bindValue(":listID", $this->getListID(), SQLITE3_INTEGER);
        $results = $stmt->execute();
        // "Cache" each user
        $this->items = array();
        while ($row = $results->fetchArray()) {
        // Inefficient but will do
            $this->items[] = new Item($row["id"]);
        }
        return $this->items;
    }
 
    // Adds an item to the list, returning itemID
    public function addItem($name, $due, $longitude, $latitude) {
        // Add to DB and cache
        $this->items[] = new Item($this->getListID(), $listID, $name, $due, $longitude, $latitude);
    }
    
    // Deletes an item from the list
    public function deleteItem($itemID) {
        // Delete from DB
        $this->items[$itemID]->delete();
        // And from cache
        unset($this->items[$itemID]);
    }
    
}

?>