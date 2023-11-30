<?php

/**
 * Autoload all dependencies and configurations into application
 * NOTICE: Do not change the position of lines of code
 */

// ENV
define("DEV_ENV", "development");
define("PROD_ENV", "production");
define("ENV", DEV_ENV);

// PHP CONFIG
error_reporting(ENV === PROD_ENV ? 0 : E_ALL);

// APP_CONFIG
define("APP_URL", "http://simple-php.test/");
define("APP_VERSION", "0.0.1");
define("APP_MAX_REQUEST_PER_MINUTE", 30);
define("APP_HANDLED_URL", str_ends_with(APP_URL, "/") ? APP_URL : APP_URL . "/");

// DIRECTORIES
define("ROOT_DIR", str_replace("\\", "/", __DIR__));
define("CONTROLLER_DIR", ROOT_DIR . "/app/controllers");
define("MODEL_DIR", ROOT_DIR . "/app/models");
define("REPOSITORY_DIR", ROOT_DIR . "/app/repositories");
define("VIEW_DIR", ROOT_DIR . "/app/views");
define("CORE_DIR", ROOT_DIR . "/core");
define("STORAGE_DIR", ROOT_DIR . "/storage");
define("VENDOR_DIR", ROOT_DIR . "/vendors");

// KEYS
define("THROTTLE_REQUEST_KEY", "THROTTLE_REQUEST_KEY");
define("XSRF_TOKEN_KEY", "XSRF_TOKEN_KEY");

// EMAILS
define("EMAIL_HOST", "smtp.example.com");
define("EMAIL_USERNAME", "user@example.com");
define("EMAIL_PASSWORD", "secret");
define("EMAIL_PORT", 465);

// VENDORS
require_once(VENDOR_DIR . "/PHPMailer-6.8.1/PHPMailer.php");
require_once(VENDOR_DIR . "/PHPMailer-6.8.1/SMTP.php");

// CORE
require_once(CORE_DIR . "/request.php");
require_once(CORE_DIR . "/response.php");
require_once(CORE_DIR . "/model.php");
require_once(CORE_DIR . "/repository.php");
require_once(CORE_DIR . "/controller.php");

// MODELS
require_once(MODEL_DIR . "/user.model.php");

// REPOSITORIES
require_once(REPOSITORY_DIR . "/user.repo.php");

// CONTROLLERS
require_once(CONTROLLER_DIR . "/home.controller.php");
