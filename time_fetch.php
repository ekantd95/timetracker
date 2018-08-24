<?php
session_start();
require ('timetracker_connect.php');
include ('functions.php');
$timezone = $_SESSION['time'];


if ( isset($_POST['first_timestamp']) && isset($_POST['last_timestamp']) ) {
    $q = "SELECT event_name, start_event, category, length, time_saved, UNIX_TIMESTAMP(time_saved) as tim
    from transitions
    where UNIX_TIMESTAMP(time_saved) > {$_POST['first_timestamp']}
    and UNIX_TIMESTAMP(time_saved) < {$_POST['last_timestamp']}
    order by time_saved asc;";

    $r = mysqli_query($dbc, $q);

    if ($r) {

        if (mysqli_num_rows($r) == 0) {
            echo 'no markers';
        } else {
            // set up array of markers
            $markers = array();
            while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                $markers[] = $row;
            }

            // filter into various days
            $midnights = [];



            // check for topoff
            $last_timestamp = $markers[count($markers) - 1]['tim'];

            $q = "SELECT event_name, category, UNIX_TIMESTAMP(time_saved) as tim
            FROM transitions
            WHERE UNIX_TIMESTAMP(time_saved) > $last_timestamp
            ORDER BY time_saved ASC LIMIT 1;";
            $r = mysqli_query($dbc, $q);
            if (mysqli_num_rows($r) > 0) {
                $single_row = mysqli_fetch_array($r);
                if ($single_row['start_event'] == 1) {
                    $topoff = 'none';
                } else {
                    $topoff = $single_row;
                }
            } else {
                $topoff = 'none';
            }


            $data = array($markers, $topoff);
            $data = json_encode($data);
            echo $data;
            // if (!isset($data)) {
            // } else {
            //     echo 'errors: ' . mysqli_error($dbc);
            // }
        } // there was actual data;
        } else { // query didn't work
            echo 'errors:' . mysqli_error($dbc);
        }
    } else { // both timestamps weren't sent
    echo 'Missing timestamps';
}
