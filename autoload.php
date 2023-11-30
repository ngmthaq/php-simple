<?php

/**
 * Autoload all dependencies and configurations into application
 * NOTICE: Do not change the position of lines of code
 */

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
