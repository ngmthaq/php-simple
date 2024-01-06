<?php

try {
    // Start session;
    session_start();

    // Collect output buffer
    ob_start();

    // Autoload ENV
    require_once("../env.php");

    // Autoload dependencies
    require_once("../autoload.php");

    // Autoload helpers
    require_once("../helpers.php");

    // Check service is running or not
    if (!PHP_SERVICE_RUNNING) {
        $response = new Response();
        $response->error(HTTP_STT_SERVICE_UNAVAILABLE, "The server is not ready to handle the request", "Service Unavailable");
        exit();
    }

    // Get controller
    $raw_controller_name = isset($_GET["c"]) ? $_GET["c"] : null;
    if (empty($raw_controller_name)) $raw_controller_name = "home";
    $controller_name = ucfirst($raw_controller_name) . "Controller";

    // Get method
    $action_name = isset($_GET["a"]) ? $_GET["a"] : null;
    if (empty($action_name)) $action_name = "index";

    // Invoke controller
    $query = isset($_SERVER["QUERY_STRING"]) ? "?" . $_SERVER["QUERY_STRING"] : "";
    $uri = isset($_SERVER["REQUEST_URI"]) ? str_replace($query, "", $_SERVER["REQUEST_URI"]) : "";
    if ($uri === "/") {
        $controller = new $controller_name();
        $controller->$action_name();
        exit();
    } else {
        // Handle not found error for static files (404)
        ob_end_clean();
        http_response_code(HTTP_STT_NOT_FOUND);
        exit();
    }
} catch (\Throwable $th) {
    // Show error page
    $response = new Response();
    if ($th->getMessage() === 'Class "' . $controller_name . '" not found') {
        $response->error(HTTP_STT_NOT_FOUND, "Sorry, we cannot found the page you are looking for", "Not Found");
    } elseif ($th->getMessage() === "Call to undefined method $controller_name::$action_name()") {
        $response->error(HTTP_STT_NOT_FOUND, "Sorry, we cannot found the page you are looking for", "Not Found");
    } elseif ($th->getMessage() === "Throttle Exception") {
        $response->error(HTTP_STT_TOO_MANY_REQUEST, "You have sent too many requests in a short period of time, please try again in a few minutes", "Too many requests");
    } elseif ($th->getMessage() === "CSRF Exception") {
        $response->error(HTTP_STT_FORBIDDEN, ENV === PROD_ENV ? "You do not have permission to access this site" : "CSRF Token Mismatch", "Forbidden");
    } else {
        $error = $th->getMessage() . " (" . $th->getFile() . ":" . $th->getLine() . ")";
        systemLogError($error);
        $response->error(HTTP_STT_INTERNAL_SERVER_ERROR, ENV === PROD_ENV ? "Something went wrong, please try again later" : $error, "Server Internal Error");
    }
    exit();
}
