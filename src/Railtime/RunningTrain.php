<?php

namespace Railtime;

/**
 * A running train is between origin and destination
 *
 * @package Railtime
 */
class RunningTrain extends RailtimeObject {
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $date;

    /**
     * One of [Railtime\StatusNotRunning, Railtime\StatusRunning]
     *
     * @var string
     */
    public $status;

    /**
     * @var float
     */
    public $latitude;

    /**
     * @var float
     */
    public $longitude;

    /**
     * Human-readable message describing the train's code,
     * time, direction, punctuality, and next stop
     *
     * @var string
     */
    public $message;

    /**
     * Northbound, Southbound, or "To XYZ"
     * One of TrainDirection constants
     *
     * @var string
     */
    public $direction;
}
