<?php

namespace Railtime;

/**
 * Private Utility class
 *
 * This class isn't ideal (using static methods), currently only for internal use.
 *
 * @package Railtime
 */
class Utility {
    /**
     * Validate a time string such as "9:15" or "06:30:45"
     *
     * @param string $time_string
     * @return bool
     */
    public static function is_valid_time_string($time_string) {
        if (!is_string($time_string)) {
            return false;
        }
        $regex = "/^(0?[0-9]|1[0-9]|2[0-3])(:(0?[0-9]|[1-5][0-9]|60)){1,2}$/";
        return (preg_match($regex, $time_string) > 0);
    }

    /**
     * Parse a datetime string of the format "23 dec 2011 15:00(:00)?"
     *
     * @param string $datetime_string
     * @param string $timezone
     * @return \DateTime
     */
    public static function datetime_string_to_datetime($datetime_string, $timezone = Timezone) {
        return new \DateTime($datetime_string, new \DateTimeZone($timezone));
    }
}
