<?php

/**
 * Load the composer autoloader before running tests.
 */
require_once dirname(__DIR__) . "/vendor/autoload.php";

/**
 * Ensure the \Railtime constants are loaded.
 */
require_once dirname(__DIR__) . "/src/Railtime/API.php";
