<?php
/**
 * Updates a list into the DB
 * Takes name, and uses authenticated session user
 */
 
include __DIR__ . "/../../common/helpers.php";
include __DIR__ . "/../../common/access.php";

$json = getJSON();

$response = array(
    "success" => false
    );
 
if (loggedIn() && hasStrings($json, array("name","id"))) {
    $user = new User($_SESSION["user"]);
    $list = $user->getList($json->id);
    $list->setName($json->name);
    $response["success"] = true;
} else {
    $response["message"] = "not authorised/check params";
} 
 
echo json_encode($response);
 
?>