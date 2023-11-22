<?php

// Start collect output buffer
ob_start();

// Start session;
session_start();

// Autoload
require_once("../autoload.php");

// Autoload helpers
require_once("../helpers.php");

// Get controller
$raw_controller_name = isset($_GET["c"]) ? $_GET["c"] : null;
if (empty($raw_controller_name)) $raw_controller_name = "home";
$controller_name = ucfirst($raw_controller_name) . "Controller";

// Get method
$action_name = isset($_GET["a"]) ? $_GET["a"] : null;
if (empty($action_name)) $action_name = "index";

try {
    // Invoke controller
    $query = isset($_SERVER["QUERY_STRING"]) ? "?" . $_SERVER["QUERY_STRING"] : "";
    $uri = isset($_SERVER["REQUEST_URI"]) ? str_replace($query, "", $_SERVER["REQUEST_URI"]) : "";
    if ($uri === "/") {
        $controller = new $controller_name();
        $controller->$action_name();
    } else {
        // Handle not found error for static files (404)
        ob_end_clean();
        http_response_code(404);
    }
} catch (\Throwable $th) {
    // Show error page
    ob_end_clean();
    if ($th->getMessage() === 'Class "' . $controller_name . '" not found') {
        http_response_code(404);
        echo "404 Not Found";
    } elseif ($th->getMessage() === "Call to undefined method $controller_name::$action_name()") {
        http_response_code(404);
        echo "404 Not Found";
    } elseif ($th->getMessage() === "Throttle Exception") {
        http_response_code(429);
        echo "Throttle Exception";
    } elseif ($th->getMessage() === "CSRF Exception") {
        http_response_code(403);
        echo "CSRF Exception";
    } else {
        http_response_code(500);
        echo ENV === PROD_ENV ? "Server Internal Error" : $th->getMessage();
    }
}
