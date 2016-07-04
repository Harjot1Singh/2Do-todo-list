<?php
/**
 * Contains PHP helper functions
**/

// Turns on detailed error reporting
error_reporting(E_ALL); 
ini_set("display_errors", 1);
session_start_once();

// Autoloads models
spl_autoload_register(function($class_name) {
    $file = __DIR__ . "/../models/" . $class_name. ".php";
    if (file_exists($file)) {
        include_once($file);
    }
});

// Autoload common files
spl_autoload_register(function($class_name) {
    $file = __DIR__ . "/../common/" . $class_name. ".php";
    if (file_exists($file)) {
        include_once($file);
    }
});

// Autoload anything else
spl_autoload_register(function($class_name) {
    $file = __DIR__ . "/../" . $class_name. ".php";
    if (file_exists($file)) {
        include_once($file);
    }
});

// Only starts a session if it hasn't already been started
function session_start_once() {
    if (session_id() == '')
        session_start();
}

// Wrapper for reading JSON input
function getJSON() {
    return json_decode(file_get_contents("php://input"));
}

// Checks for non-null property strings on an object
function hasStrings($obj, $properties) {
    foreach ($properties as $property) {
        if (!isset($obj->$property))
            return false;
    }
    return true;
}

?>