<?php

namespace Railtime;

/**
 * Railway station
 *
 * @package Railtime
 */
class Station extends RailtimeObject {
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $alias;

    /**
     * @var float
     */
    public $latitude;

    /**
     * @var float
     */
    public $longitude;
}
