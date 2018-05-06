<?php 
/**
 * User class
 * Must call one of the login methods, or the create method to obtain a userID
 * so it becomes usable. If userID is known, pass it into constructor.
 */
include_once __DIR__ . "/../common/helpers.php";

session_start_once();

class User {
    private $userID;
    private $db;
    private $lists;
    
    // Set up DB on construction
    public function __construct($userID = null) {
    	$this->userID = $userID;
    	$this->db = new Database();
    }
    
    // Re-set up DB on deserialisation (read of $_SESSION["user"] on another page)
    public function __wakeup () {
    	$this->db = new Database();
    }
    
    // Returns userID
	public function getUserID() {
	    return $this->userID;
	}
	
	// Creates a new user, and sets userID. ALLOWS FOR DUPLICATE EMAIL
	public function create($name, $email, $password) {
        // Produce hashed + salted password
	    $hashed_password = $this->generateHash($password);
	    // Store in DB
        $stmt = $this->db->prepare("INSERT INTO users VALUES (NULL, :name, :email, :password, NULL, NULL, NULL)");
        $stmt->bindValue(":name", $name, SQLITE3_TEXT);
        $stmt->bindValue(":email", $email, SQLITE3_TEXT);
        $stmt->bindValue(":password", $hashed_password, SQLITE3_TEXT);
        $stmt->execute();
        // Finally set userID
        $this->userID = $this->db->lastRowID();
        $_SESSION["user"] = $this->getUserID();
        return true;
	}
	
	// Returns true if OAuth login to Facebook was successful
	public function loginFacebook() {
        // TODO 
	}
	
	// Returns true if the password is correct
	public function loginEmail($email, $password) {
	    // Retrieve the hashed password and check it
        $stmt = $this->db->prepare("SELECT id, password FROM users WHERE email = :email");
        $stmt->bindValue(":email", $email, SQLITE3_TEXT);
        $results = $stmt->execute();
        // Only get first row
        $row = $results->fetchArray();
        $hashed_password = $row["password"];
        // If the password is correct, set up session and userID
        if ($this->checkHash($password, $hashed_password)) {
        	$this->userID = $row["id"];
        	// Store user in session
        	$_SESSION["user"] = $this->getUserID();
        	return true;
        } else
        	return false;
	}
	
	// Returns true if the user is logged in i.e. the session is setup correctly
	public function isLoggedIn() {
		return isset($_SESSION["user"]);
	}
	
	// Logs user out and returns self
	public function logout() {
        unset($_SESSION["user"]);
        return $this;
	}
	
	// Changes the user's password
	public function setPassword($password) {
	    // Produce hashed + salted password
	    $hashed_password = generateHash($password);
	    // Store in DB
	    $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :userID");
        $stmt->bindValue(":userID", $this->getUserID(), SQLITE3_INTEGER);
        $stmt->bindValue(":password", $hashed_password, SQLITE3_TEXT);
        $stmt->execute();
	}
	
	// Generate and return auth code and store it in DB
	public function generateVerifyCode ($phoneNumber) {
		// Generate random 6 digit auth code
		$auth_code = rand(100000, 999999);
		// Insert into DB
		$stmt = $this->db->prepare("UPDATE users SET verifyCode = :verifyCode, phone = :phoneNumber WHERE id = :userID");
        $stmt->bindValue(":userID", $this->getUserID(), SQLITE3_INTEGER);
        $stmt->bindValue(":verifyCode", $auth_code, SQLITE3_TEXT);
        $stmt->bindValue(":phoneNumber", $phoneNumber, SQLITE3_TEXT);
        $stmt->execute();
		return $auth_code;
	}
	
	// Returns true if auth code matches, and removes it from DB
	public function validateVerifyCode ($code) {
		// Get code from DB
		$stmt = $this->db->prepare("SELECT verifyCode FROM users WHERE id = :userID");
        $stmt->bindValue(":id", $this->getUserID(), SQLITE3_INTEGER);
        $results = $stmt->execute();
        // Only get first row
        $row = $results->fetchArray();
        $stored_code = $row["verifyCode"];
        // Set the verifyCode field to verified
        if ($stored_code === $code) {
        	$stmt = $this->db->prepare("UPDATE users SET verifyCode = :verifyCode WHERE id = :userID");
        	$stmt->bindValue(":userID", $this->getUserID(), SQLITE3_INTEGER);
        	$stmt->bindValue(":verifyCode", "verified", SQLITE3_TEXT);
        	$stmt->execute();	
        	return true;
        } else
        	return false;
	}
	
	// Returns phone number if it has been verified, or false otherwise
	public function getVerifiedPhoneNumber() {
		// Get code from DB
		$stmt = $this->db->prepare("SELECT phone, verifyCode FROM users WHERE id = :userID");
        $stmt->bindValue(":id", $this->getUserID(), SQLITE3_INTEGER);
        $results = $stmt->execute();
        // Only get first row
        $row = $results->fetchArray();
        $stored_code = $row["verifyCode"];
        // Check if the number has been verified and return it if it has
        if ($stored_code === "verified") 
        	return $row["phone"];
        else
        	return false;
	}
	
	
	// Returns password hash using blowfish of cost 10 with 22 random char salt 
	private function generateHash($password) {
        return crypt($password, '$2a$10$'.substr(sha1(mt_rand()),0,22));
    }

    // Returns true if the hash matches the given password
    private function checkHash($password, $hash) {
        // Works without retrieving/storing salt, crypt stores it in $hash
        // See http://www.yiiframework.com/wiki/425/use-crypt-for-password-storage/
        return ($hash === crypt($password, $hash));
    }
	
	// Gets all lists for the user
	public function getLists() {
		// Check cache
		//if (isset($this->lists))
		//	return $this->lists;
		// Otherwise pull from DB
		$stmt = $this->db->prepare("SELECT * FROM userlists WHERE userID = :userID");
        $stmt->bindValue(":userID", $this->getUserID(), SQLITE3_INTEGER);
        $results = $stmt->execute();
        // "Cache" each list
        $this->lists = array();
        while ($row = $results->fetchArray()) {
        	$listID = $row["listID"];
        	// Create a list object for each of the list IDs
            $this->lists[$listID] = new TodoList(null,null, $listID);
        }
        return $this->lists;
	}
	
	// Gets a list for the user, returns false if user doesn't have access to it
	public function getList($listID) {
		// Check cache
		//if (isset($this->lists))
		//	return $this->lists;
		// Otherwise pull from DB
		$stmt = $this->db->prepare("SELECT 1 FROM userlists WHERE userID = :userID AND listID = :listID");
        $stmt->bindValue(":userID", $this->getUserID(), SQLITE3_INTEGER);
        $stmt->bindValue(":listID", $listID, SQLITE3_INTEGER);
        $results = $stmt->execute();
        // "Cache" each list
        $this->lists = array();
        $row = $results->fetchArray();
        if ($row) {
            $list = new TodoList(null,null, $listID);
            return $list;
        } else
            return false;
	}
	
	// Adds a new list for the user
    public function addList($name) {
        // Add to DB
        $list = new TodoList($this->getUserID(), $name, null);
        // Cache it
        $listID = $list->getListID();
        $this->lists[$listID] = $list;
        return $listID;
    }
    
	// Deletes a list for the user
    public function deleteList($listID) {
        $list = new TodoList(null, null, $listID);
        $list->delete();
    }

}

?>