<?php

namespace Railtime;

/**
 * An actual (or expected) train passing through a station
 *
 * @package Railtime
 */
class StationPassing extends RailtimeObject {
    /**
     * @var string
     */
    public $server_time;

    /**
     * @var string
     */
    public $train_code;

    /**
     * @var string
     */
    public $station_code;

    /**
     * @var string
     */
    public $station_fullname;

    /**
     * @var string
     */
    public $query_time;

    /**
     * @var string
     */
    public $train_date;

    /**
     * @var string
     */
    public $origin;

    /**
     * @var string
     */
    public $destination;

    /**
     * @var string
     */
    public $origin_time;

    /**
     * @var string
     */
    public $destination_time;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $last_location;

    /**
     * How many minutes the train is due to arrive in
     *
     * @var int
     */
    public $due_minutes;

    /**
     * How many minutes the train is late by
     *
     * @var int
     */
    public $late_minutes;

    /**
     * @var string
     */
    public $expected_arrival;

    /**
     * @var string
     */
    public $expected_departure;

    /**
     * @var string
     */
    public $scheduled_arrival;

    /**
     * @var string
     */
    public $scheduled_departure;

    /**
     * @var string
     */
    public $direction;

    /**
     * @var string
     */
    public $train_type;

    /**
     * One of:  [Railtime\LocationTypeOrigin,
     *          Railtime\LocationTypeStop,
     *          Railtime\LocationTypeTimingPoint,
     *          Railtime\LocationTypeDestination]
     *
     * @var string
     */
    public $location_type;
}
