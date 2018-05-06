<?php
/**
 * Logs out user and redirects them to login page.
 * DOES NOT RETURN ANYTHING OTHER THAN A REDIRECT
 */ 
include_once __DIR__ . "/../../common/helpers.php";

if (isset($_SESSION["user"])) {
    $user = new User($_SESSION["user"]);
    $user->logout();
}
header("Location:../../login.php");

?>