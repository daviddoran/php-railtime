<?php

use Railtime\TrainMovement;

class TrainMovementTest extends \PHPUnit_Framework_TestCase {
    /**
     * @dataProvider timeProvider
     * @param string|null $input
     * @param bool $expected_validity
     */
    public function testIsArrived($input, $expected_validity) {
        $tm = new TrainMovement;
        $tm->actual_arrival = $input;
        $this->assertEquals($expected_validity, $tm->is_arrived());
    }

    /**
     * @dataProvider timeProvider
     * @param string|null $input
     * @param bool $expected_validity
     */
    public function testIsDeparted($input, $expected_validity) {
        $tm = new TrainMovement;
        $tm->actual_departure = $input;
        $this->assertEquals($expected_validity, $tm->is_departed());
    }

    /**
     * @dataProvider timeDiffProvider
     * @param string $scheduled_time
     * @param string $actual_time
     * @param int $expected_difference
     */
    public function testArrivalDiffSeconds($scheduled_time, $actual_time, $expected_difference) {
        $tm = new TrainMovement;
        $tm->scheduled_arrival = $scheduled_time;
        $tm->actual_arrival = $actual_time;
        $this->assertEquals($expected_difference, $tm->arrival_diff_seconds());
    }

    /**
     * @dataProvider timeDiffProvider
     * @param string $scheduled_time
     * @param string $actual_time
     * @param int $expected_difference
     */
    public function testDepartureDiffSeconds($scheduled_time, $actual_time, $expected_difference) {
        $tm = new TrainMovement;
        $tm->scheduled_departure = $scheduled_time;
        $tm->actual_departure = $actual_time;
        $this->assertEquals($expected_difference, $tm->departure_diff_seconds());
    }

    /**
     * @expectedException \Railtime\Exception
     * @dataProvider invalidTimeDiffProvider
     * @param string|mixed $scheduled_time
     * @param string|mixed $actual_time
     */
    public function testArrivalDiffInvalidTime($scheduled_time, $actual_time) {
        $tm = new TrainMovement;
        $tm->scheduled_arrival = $scheduled_time;
        $tm->actual_arrival = $actual_time;
        $tm->arrival_diff_seconds();
    }

    /**
     * @expectedException \Railtime\Exception
     * @dataProvider invalidTimeDiffProvider
     * @param string|mixed $scheduled_time
     * @param string|mixed $actual_time
     */
    public function testDepartureDiffInvalidTime($scheduled_time, $actual_time) {
        $tm = new TrainMovement;
        $tm->scheduled_departure = $scheduled_time;
        $tm->actual_departure = $actual_time;
        $tm->departure_diff_seconds();
    }

    /**
     * Provides time1, time2, and difference between them in seconds
     *
     * @return array
     */
    public function timeDiffProvider() {
        $MINUTE = 60;
        $HOUR = 60 * 60;
        return array(
            array("6:15", "9:45", 3 * $HOUR + 30 * $MINUTE), //time (no leading zeros)
            array("23:04", "23:11", 7 * $MINUTE), //time
            array("23:04:00", "23:11:00", 7 * $MINUTE), //time (zero seconds)
            array("23:04:11", "23:11:55", 7 * $MINUTE + 44), //time (with seconds)
            array("00:00:00", "23:59:59", 24 * $HOUR - 1), //full day (minus a second)

            array("7:00", "6:58", - (2 * $MINUTE)), //ahead of schedule
            array("07:00", "06:58", - (2 * $MINUTE)), //ahead of schedule
        );
    }

    /**
     * Provides invalid time1, time2 or both
     *
     * @return array
     */
    public function invalidTimeDiffProvider() {
        return array(
            array("", "9:45"),
            array("9:45", ""),
            array("", ""),
            array(null, null)
        );
    }

    /**
     * Provides an array of two elements:
     *   - A valid time string or mixed
     *   - true/false whether the time string was valid
     *
     * @return array
     */
    public function timeProvider() {
        return array(
            array("10:19:24", true),
            array("12:55", true),
            array("9:15", true),
            array("26:12", false),
            array("", false),
            array(null, false),
        );
    }
}
