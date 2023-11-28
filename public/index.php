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
    $response = new Response();
    if ($th->getMessage() === 'Class "' . $controller_name . '" not found') {
        $response->error(404, "Sorry ! We cannot found the page you are looking for !", "Not Found");
    } elseif ($th->getMessage() === "Call to undefined method $controller_name::$action_name()") {
        $response->error(404, "Sorry ! We cannot found the page you are looking for !", "Not Found");
    } elseif ($th->getMessage() === "Throttle Exception") {
        $response->error(429, "You have sent too many requests in a short period of time. Please try again in a few minutes !", "Too many requests");
    } elseif ($th->getMessage() === "CSRF Exception") {
        $response->error(403, ENV === PROD_ENV ? "You do not have permission to access this site !" : "CSRF Token Mismatch", "Forbidden");
    } else {
        $response->error(500, ENV === PROD_ENV ? "Oops ! Server Internal Error !" : $th->getMessage(), "Server Internal Error");
    }
}
