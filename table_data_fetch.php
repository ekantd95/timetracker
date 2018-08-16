<?php

    // hook up database
    require ('timetracker_connect.php');

    // compile available event data for table
    $q2 = "SELECT event_name, category, length, start_event, UNIX_TIMESTAMP(time_saved) as tim
    FROM transitions
    ORDER BY time_saved DESC LIMIT 18;";
    $r = mysqli_query($dbc, $q2);

    // create table
    echo '<table>';
    echo '<tr><th>Name</th><th>Category</th><th>Length</th><th>TimeSaved</th></tr>';
    while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

        if (!isset($row['start_event'])) {

            echo '<tr>';

            $event_length = $row['length'];
            // format an event length for the table
            if ($event_length < 60) { // display in seconds

                $formatted_event_length = $event_length . ' sec';

            } else if ($event_length < 3600) { // display in minutes and seconds

                $remainder_seconds = $event_length % 60;
                $minutes = ($event_length - $remainder_seconds) /60;

                $formatted_event_length = $minutes . ' min, '. $remainder_seconds . ' sec';

            } else { // display in hours minutes and seconds

                $seconds = $event_length % 3600;
                $hours = ($event_length - $seconds) / 3600;
                $remainder_seconds = $seconds % 60;
                $minutes = ($seconds - $remainder_seconds) / 60;

                $formatted_event_length = $hours . ' hrs, ' . $minutes . ' min, ' . $remainder_seconds . ' sec ';

            }

            echo '<td>' . $row['event_name'] . '</td>
            <td>' . $row['category'] . '</td>
            <td>' . $formatted_event_length . '</td>
            <td>' . date('m/d H:i:s', $row['tim']) . '</td>';

            echo '</tr>';

        } // as long as it isn't a start event

    } // per row

    echo '</table>';
