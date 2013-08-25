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
        return Utility::is_valid_time_string($this->actual_arrival);
    }

    /**
     * @return bool
     */
    public function is_departed() {
        return Utility::is_valid_time_string($this->actual_departure);
    }

    /**
     * How many seconds the actual arrival was ahead/behind of schedule
     *
     * Note: a negative result means the train arrived ahead of schedule (early).
     *
     * @return int
     * @throws Exception
     */
    public function arrival_diff_seconds() {
        if (!$this->is_arrived()) {
            throw new Exception("Train has not arrived yet. Can't calculate difference from scheduled arrival time.");
        }
        if (!Utility::is_valid_time_string($this->scheduled_arrival)) {
            throw new Exception("This train movement does not have a valid scheduled arrival time.");
        }

        //TODO: Handle the 00:00:00 case!
//        if (in_array("00:00:00", array($this->scheduled_arrival, $this->actual_arrival))) {
//            return 0;
//        }

        $scheduled = Utility::datetime_string_to_datetime($this->train_date . " " . $this->scheduled_arrival);
        $actual = Utility::datetime_string_to_datetime($this->train_date . " " . $this->actual_arrival);

        return $actual->getTimestamp() - $scheduled->getTimestamp();
    }

    /**
     * How many seconds the actual departure was ahead/behind of schedule
     *
     * Note: a negative result means the train departed ahead of schedule (early).
     *
     * @return int
     * @throws Exception
     */
    public function departure_diff_seconds() {
        if (!$this->is_departed()) {
            throw new Exception("Train has not departed yet. Can't calculate difference from scheduled departure time.");
        }
        if (!Utility::is_valid_time_string($this->scheduled_departure)) {
            throw new Exception("This train movement does not have a valid scheduled departure time.");
        }

        //TODO: Handle the 00:00:00 case!
//        if (in_array("00:00:00", array($this->scheduled_arrival, $this->actual_arrival))) {
//            return 0;
//        }

        $scheduled = Utility::datetime_string_to_datetime($this->train_date . " " . $this->scheduled_departure);
        $actual = Utility::datetime_string_to_datetime($this->train_date . " " . $this->actual_departure);

        return $actual->getTimestamp() - $scheduled->getTimestamp();
    }
}
