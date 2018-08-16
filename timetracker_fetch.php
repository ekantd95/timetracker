<?php

require ('timetracker_connect.php');
include ('functions.php');

if (isset($_POST['scale_number'])) {

    $scale_number = $_POST['scale_number'];

    if ($scale_number == 9) {
        $q = "SELECT event_name, start_event, category, length, time_saved, UNIX_TIMESTAMP(time_saved) as tim
        from transitions
        where CURDATE() = date(time_saved)
        and HOUR(time_saved) >= 8
        and HOUR(time_saved) < 17
        order by time_saved asc;";


    } else if ($scale_number == 24) { // scale number is 24
        $q = "SELECT event_name, start_event, category, length, time_saved, UNIX_TIMESTAMP(time_saved) as tim
        FROM transitions
        WHERE CURDATE() = DATE(time_saved)
        ORDER BY time_saved ASC;";
    } else { // scale number is 1
        $q = "SELECT event_name, start_event, category, length, time_saved, UNIX_TIMESTAMP(time_saved) as tim
        FROM transitions
        WHERE DATE(time_saved) = CURDATE()
        AND UNIX_TIMESTAMP(time_saved) > ( UNIX_TIMESTAMP( NOW() ) - 3600 )
        ORDER BY time_saved ASC;";
    }

    $r = mysqli_query($dbc, $q);


    if ($r) {
        if (mysqli_num_rows($r) == 0) {
            echo 'no markers';
        } else {

            while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                $markers[] = $row;
            }

            // check for topoff if scale_number is 9 or 24
            if ($scale_number !== 1) {
                $last_timestamp = $markers[count($markers) - 1]['tim'];

                $q = "SELECT event_name, start_event, category, length, time_saved, UNIX_TIMESTAMP(time_saved) as tim
                FROM transitions
                WHERE UNIX_TIMESTAMP(time_saved) > $last_timestamp
                ORDER BY time_saved ASC LIMIT 1;";
                $r = mysqli_query($dbc, $q);
                $single_row = mysqli_fetch_array($r);
                if ($single_row['start_event'] == 1) {
                    $topoff = 1;
                } else {
                    $topoff = 0;
                }

            } // end of if (scale !== 1)

            $data = array($markers, $topoff);
            $data = json_encode($data);
            echo $data;
        }
    } else {
        echo 'Query failed';
    }

} else {
    echo "scale_number not set";
}
