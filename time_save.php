<?php
session_start();
// hook up database
require ('timetracker_connect.php');


if (isset($_POST['time_saved'])) {
    $date = time();
} else {
    $date = '' . $_POST['day'] . ' ' . $_POST['hour'] . ':' . $_POST['minute'] . ':' . $_POST['second'];
}

// if it's a start event
if ($_POST['start_event'] == 'true') {
    // make sure previous transition is not a start event
    $q = "SELECT start_event from transitions
    where time_saved < FROM_UNIXTIME($date)
    and user_id={$_SESSION['timetracker_user_id']}
    order by  time_saved
    desc limit 1";
    $r = mysqli_query($dbc, $q);
    $row_1 = mysqli_fetch_assoc($r);

    if ($row_1['start_event'] == 1) { // previous event was a start event
        echo "Cannot insert multiple start events consecutively";
    } else { // previous event was an end
        // compile and run query to insert event into database
        $q = "INSERT INTO transitions (time_saved, start_event, user_id)
        VALUES (FROM_UNIXTIME($date), 1, {$_SESSION['timetracker_user_id']})";
        echo $q;
        $r = mysqli_query($dbc, $q);
        echo "Start event entered";
    }
} else { // normal event end

    // pull values into variables for insert
    $event_name = mysqli_real_escape_string($dbc, $_POST['event_name']);
    $category = mysqli_real_escape_string($dbc, $_POST['category']);

    // compile and run query to insert event into database
    $q = "INSERT INTO transitions (event_name, category, time_saved, user_id)
    VALUES ('$event_name', '$category', FROM_UNIXTIME($date), {$_SESSION['timetracker_user_id']});";
    $r = mysqli_query($dbc, $q);

    if ($r) { // upon successful insert
        echo "success";
    } else { // if ($r) for insert query
        echo 'insert query didn\'t work';
        echo 'error: ' . mysqli_error($dbc);
    }

} // normal event end
