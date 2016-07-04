<?php
/**
 * Deletes a list for the user given a list ID
 */

include __DIR__ . "/../../common/helpers.php";
include __DIR__ . "/../../common/access.php";

$json = getJSON();

$response = array(
    "success" => false
    );

if (loggedIn() && isset($json->listID)) {
    $user = new User($_SESSION["user"]);
    $user->deleteList($json->listID);
    $response["success"] = true;
} else {
    $response["message"] = "not authorised";
}

echo json_encode($response);

?>