<?php
    session_start();
    // hook up database
    require ('timetracker_connect.php');
    include ('functions.php');

    // compile available event data for table
    $q1 = "SELECT event_name, category, start_event, UNIX_TIMESTAMP(time_saved) as tim
    from transitions
    where user_id={$_SESSION['timetracker_user_id']}
    order by time_saved desc limit 15;";

    $r = mysqli_query($dbc, $q1);

    // create table
    echo '<table id="tracker_table" class="results_table">';
    echo '<tr><th>Name</th><th>Category</th><th>Length</th><th>Time saved</th></tr>';

    if (mysqli_num_rows($r) > 0) {

        $rows = [];
        while($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
            $rows[] = $row;
        }

        for ($i = 0; $i < count($rows); $i++) { // output html for each row

            // skip if it's a start event
            if (!isset($rows[$i]['start_event'])) {

                echo '<tr>';
                if ($i == count($rows) - 1) { // on last row query for the row just older to calculate length
                    $q2 = "SELECT time_saved, UNIX_TIMESTAMP(time_saved) as tim
                    from transitions
                    where UNIX_TIMESTAMP(time_saved) < {$rows[$i]['tim']}
                    and user_id={$_SESSION['timetracker_user_id']}
                    order by time_saved desc limit 1";
                    $r = mysqli_query($dbc, $q2);
                    if ($r) {
                        if (mysqli_num_rows($r) > 0) {
                            $row_1 = mysqli_fetch_array($r);
                            $event_length = $rows[$i]['time_saved'] - $row_1['tim'];
                        } else { // there weren't any older rows
                            $formatted_event_length = '-';
                        }
                    } else { // query didn't work
                        echo 'just older query didn\'t work';
                        echo 'query: ' . $q2;
                    }
                } else { // you have the data for every other row
                    $event_length = $rows[$i]['tim'] - $rows[$i + 1]['tim'];
                }

                if ($formatted_event_length !== '-') {
                    $formatted_event_length = format_time($event_length);
                }

                echo '<td>' . $rows[$i]['event_name'] . '</td>
                <td>' . $rows[$i]['category'] . '</td>
                <td>' . $formatted_event_length . '</td>
                <td>' . date('m/d H:i:s', $rows[$i]['tim']) . '</td>';

                echo '</tr>';

            } // as long as it isn't a start event

        } // for loop (each row)

    } // as long as there is at least one row to show


    echo '</table>';
