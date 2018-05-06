<?php
/**
 * Inserts an item into the DB
 * Takes itemID, and uses authenticated session user
 */
 
include __DIR__ . "/../../common/helpers.php";
include __DIR__ . "/../../common/access.php";

$json = getJSON();

$response = array(
    "success" => false
    );
 
if (loggedIn() && hasStrings($json, array("itemID", "name", "due", "urgent", "completed"))) {
    $item = new Item($json->itemID);
    $item->setItem($json->name, $json->due, null, null, $json->urgent, $json->completed);
    $response["success"] = true;
} else {
    $response["message"] = "not authorised";
} 
 
echo json_encode($response);
 
?>