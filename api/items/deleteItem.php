<?php
/**
 * Deletes an item from the DB
 * Takes itemID, and uses authenticated session user
 */
 
include __DIR__ . "/../../common/helpers.php";
include __DIR__ . "/../../common/access.php";

$json = getJSON();

$response = array(
    "success" => false
    );
 
if (loggedIn() && hasStrings($json, array("itemID"))) {
    $item = new Item($json->itemID);
    $item->delete();
    $response["success"] = true;
} else {
    $response["message"] = "not authorised";
} 
 
echo json_encode($response);
 
?>