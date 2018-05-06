<?php
/**
 * Inserts a list into the DB
 * Takes name, and uses authenticated session user
 */
 
include __DIR__ . "/../../common/helpers.php";
include __DIR__ . "/../../common/access.php";

$json = getJSON();

$response = array(
    "success" => false
    );
 
if (loggedIn()) {
    $user = new User($_SESSION["user"]);
    $listID = $user->addList("New List");
    $response["success"] = true;
    $response["listID"] = $listID;
} else {
    $response["message"] = "not authorised";
} 
 
echo json_encode($response);
 
?>