<?php 
include_once 'helpers.php';
session_start_once();

//returns true if logged in
function loggedIn() {
    return isset($_SESSION["user"]);
}

// returns true if user == resource user
function accessResource($resource_user_id) {

}

?>