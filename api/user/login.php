<?php 
/** 
 * Login Route for the different services
 **/
include __DIR__ . "/../../common/helpers.php";

/* Functions Start */

// Logs in the user given an email and password
function loginEmail($json, $user) {
    $email = $json->email;
    $password = $json->password;
    // Check login and return boolean
    return $user->loginEmail($email, $password);
}

/* Functions End */
$json = getJSON();

$success = false;

// Set up blank user
$user = new User();

// Check whether FB login or email login etc was triggered
switch ($json->service) {
    case "email": $success = loginEmail($json, $user); break;
}

$response = array(
        "success" => $success,
        "id" => $user->getUserID(),
    );

// Return JSON response
echo json_encode($response);
?>