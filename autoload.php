<?php

// ENV
define("APP_URL", "http://simple-php.test/");
define("APP_VERSION", "0.0.1");
define("MAX_REQUEST_PER_MINUTE", 30);
define("HANDLED_APP_URL", str_ends_with(APP_URL, "/") ? APP_URL : APP_URL . "/");
define("DEV_ENV", "development");
define("PROD_ENV", "production");
define("ENV", DEV_ENV);

// DIRECTORIES
define("ROOT_DIR", str_replace("\\", "/", __DIR__));
define("CONTROLLER_DIR", ROOT_DIR . "/app/controllers");
define("MODEL_DIR", ROOT_DIR . "/app/models");
define("REPOSITORY_DIR", ROOT_DIR . "/app/repositories");
define("VIEW_DIR", ROOT_DIR . "/app/views");
define("CORE_DIR", ROOT_DIR . "/core");
define("VENDOR_DIR", ROOT_DIR . "/vendor");

// KEYS
define("THROTTLE_REQUEST_KEY", "THROTTLE_REQUEST_KEY");
define("XSRF_TOKEN_KEY", "XSRF_TOKEN_KEY");

// VENDORS

// CORE
require_once(CORE_DIR . "/controller.php");

// MODELS

// REPOSITORIES

// CONTROLLERS
require_once(CONTROLLER_DIR . "/home.controller.php");
