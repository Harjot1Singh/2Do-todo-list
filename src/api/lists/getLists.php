<?php
/**
 * Returns an array of all the lists the authenticated user can access, with items
 */
include __DIR__ . "/../../common/helpers.php";
include __DIR__ . "/../../common/access.php";


$response = array(
    "success" => false
    );

if (loggedIn()) {
    $user = new User($_SESSION["user"]);
    $response["lists"] = array();
    $lists = $user->getLists();
    foreach ($lists as $list) {
        $dueItems = $list->getDueItems();
        $completedItems = $list->getCompletedItems();
        // Holds array of actual properties
        $dueItemList = array();
        $completedItemList = array(); 
        foreach($dueItems as $item) {
            $dueItemList[] = $item->getItem();
        }
        foreach($completedItems as $item) {
            $completedItemList[] = $item->getItem();
        }
        $response["lists"][] = array(
            "id"    => $list->getListID(),
            "name"  => $list->getName(),
            "due"   => $dueItemList,
            "completed" => $completedItemList
            );
    }
    $response["success"] = true;
} else {
    $response["message"] = "Not logged in";
}
    
echo json_encode($response);
    
?>