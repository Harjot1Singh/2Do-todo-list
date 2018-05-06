<?php
/**
 * Inserts an item into the DB
 * Takes listID, and uses authenticated session user
 */
 
include __DIR__ . "/../../common/helpers.php";
include __DIR__ . "/../../common/access.php";

$json = getJSON();

$response = array(
    "success" => false
    );
 
if (loggedIn() && isset($json->listID)) {
    $list = new TodoList(null, null, $json->listID);
    $item = $list->addItem("New Item", null, null, null);
    $itemID = $item->getItem()["id"];
    $response["success"] = true;
    $response["itemID"] = $itemID;
} else {
    $response["message"] = "not authorised";
} 
 
echo json_encode($response);
 
?>