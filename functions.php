<?php

function format_time($time_in_seconds) {

    if ($time_in_seconds < 60) { // display in seconds

        $formatted_time = $time_in_seconds . ' sec';

    } else if ($time_in_seconds < 3600) { // display in minutes and seconds

        $remainder_seconds = $time_in_seconds % 60;
        $minutes = ($time_in_seconds - $remainder_seconds) / 60;

        $formatted_time = $minutes . ' min, '. $remainder_seconds . ' sec';

    } else { // display in hours minutes and seconds

        $seconds = $time_in_seconds % 3600;
        $hours = ($time_in_seconds - $seconds) / 3600;
        $remainder_seconds = $seconds % 60;
        $minutes = ($seconds - $remainder_seconds) / 60;

        $formatted_time = $hours . ' hrs, ' . $minutes . ' min, ' . $remainder_seconds . ' sec ';

    }

    return $formatted_time;

}

function selected($option, $category) {
    if ($option == $category) {
        echo 'selected';
    }
}

function mysqliresult_to_array($r) {
    while ($row = mysqli_fetch_array($r)) {
        $result[] = $row;
    }
    return $result;
}
