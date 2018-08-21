<?php
// hook up database
require ('timetracker_connect.php');

// if it's a start event
if ($_POST['start_event'] == 'true') {
    // make sure previous transition is not a start event
    $q = "SELECT start_event FROM transitions ORDER BY  time_saved DESC LIMIT 0,1";
    $r = mysqli_query($dbc, $q);
    $row_1 = mysqli_fetch_assoc($r);

    if ($row_1['start_event'] == 1) { // previous event was a start event
        echo "Cannot insert multiple start events consecutively";
    } else { // previous event was an end
        // compile and run query to insert event into database
        $q = "INSERT INTO transitions (time_saved, start_event)
        VALUES (NOW(), 1)";
        $r = mysqli_query($dbc, $q);
        echo "Start event entered";
    }
} else { // normal event end

    // calculate length of new event
    $q = "SELECT UNIX_TIMESTAMP(time_saved) as tim
    FROM transitions
    ORDER BY time_saved DESC LIMIT 1;";
    $r = mysqli_query($dbc, $q);
    $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
    $prev_stamp = $row['tim'];
    $length = round( time() - $prev_stamp );

    // pull values into variables for insert
    $event_name = mysqli_real_escape_string($dbc, $_POST['event_name']);
    $category = mysqli_real_escape_string($dbc, $_POST['category']);
    $date = date('Y-m-d H:i:s'); // in seconds
    $time = time();
    $user_id = $_SESSION['user_id'];

    // compile and run query to insert event into database
    $q = "INSERT INTO transitions (event_name, category, length, time_saved, user_id)
    VALUES ('$event_name', '$category', '$length', NOW(), '$user_id');";
    $r = mysqli_query($dbc, $q);

    if ($r) { // upon successful insert
        echo "success";
    } else { // if ($r) for insert query
        echo 'insert query didn\'t work';
    }

} // normal event end
