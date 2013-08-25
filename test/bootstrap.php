<?php

/**
 * Load the composer autoloader before running tests.
 */
require_once dirname(__DIR__) . "/vendor/autoload.php";

/**
 * Difficult to mock
 */
class ConcreteObject extends \Railtime\Object {
    public $a;
    public $b;
}
