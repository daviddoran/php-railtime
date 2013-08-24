<?php

namespace Railtime;

class TrainMovement extends Object {
    /**
     * Train code
     *
     * @var string
     */
    public $train_code;

    /**
     * Train date
     *
     * @var string
     */
    public $train_date;

    /**
     * Station code
     *
     * @var string
     */
    public $location_code;

    /**
     * Station full name
     *
     * @var string
     */
    public $location_fullname;

    /**
     * The order in which the train will pass through the stations
     *
     * @var int
     */
    public $location_order;

    /**
     * One of:  [Railtime\LocationTypeOrigin,
     *          Railtime\LocationTypeStop,
     *          Railtime\LocationTypeTimingPoint,
     *          Railtime\LocationTypeDestination]
     *
     * @var string
     */
    public $location_type;

    /**
     * Origin station
     *
     * @var string
     */
    public $train_origin;

    /**
     * Destination station
     *
     * @var string
     */
    public $train_destination;

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
    public $actual_arrival = null;
    /**
     * @var string
     */
    public $actual_departure = null;

    /**
     * @var bool
     */
    public $auto_arrival;

    /**
     * @var bool
     */
    public $auto_departure;

    /**
     * Doesn't seem to be giving sensible results
     *
     * One of [Railtime\StopTypeCurrent, Railtime\StopTypeNext]
     *
     * @var string
     */
    public $stop_type;

    /**
     * @return bool
     */
    public function is_arrived() {
        return !is_null($this->actual_arrival) && ("" !== $this->actual_arrival);
    }

    /**
     * @return bool
     */
    public function is_departed() {
        return !is_null($this->actual_departure) && ("" !== $this->actual_departure);
    }

    public function arrival_diff() {
        if (in_array("00:00:00", array($this->scheduled_arrival, $this->actual_arrival))) {
            return 0;
        }
        //TODO: Be careful about timezones!
        $sch_arr = strtotime($this->train_date . " " . $this->scheduled_arrival);
        $act_arr = strtotime($this->train_date . " " . $this->actual_arrival);
        return (($act_arr - $sch_arr) / 60);
    }

    public function departure_diff() {
        if (in_array("00:00:00", array($this->scheduled_departure, $this->actual_departure))) {
            return 0;
        }
        //TODO: Be careful about timezones!
        $sch_dep = strtotime($this->train_date . " " . $this->scheduled_departure);
        $act_dep = strtotime($this->train_date . " " . $this->actual_departure);
        return (($act_dep - $sch_dep) / 60);
    }
}
