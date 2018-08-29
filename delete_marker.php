<?php
    session_start();
    include 'header.php';
    include 'functions.php';
    // hook up database
    require 'timetracker_connect.php';


    if (isset($_GET['transition_id']) AND is_numeric($_GET['transition_id'])) { // first time clicked

        $query = "SELECT event_name, time_saved, category
        FROM transitions
        WHERE transition_id={$_GET['transition_id']}
        and user_id={$_SESSION['timetracker_user_id']}";
        if ($r = mysqli_query($dbc,$query)) {

            $row = mysqli_fetch_array($r);
            echo '<form action="delete_marker.php" method="post">
            <p>Are you sure you want to delete this marker?</p>
            <h4>Event Name:</h4><p>' . $row['event_name'] . '</p>
            <h4>Category:</h4><p>' . $row['category'] . '</p>
            <h4>Time saved:</h4><p>' . $row['time_saved'] . '</p>
            <input type="hidden" name="transition_id" value="' . $_GET['transition_id'] . '">
            <input type="submit" name="submit" value="Delete this marker!"></p>
            </form>';

        } else { // Couldn't return the query
            echo '<p style="color: red;">Could not retrieve the blog marker because:<br>' . mysqli_error($dbc) . '.</p> <p>The query being run was: ' . $query . '</p>';
        }

    } elseif (isset($_POST['transition_id']) AND is_numeric($_POST['transition_id'])) { // confirmed

         // Handle the form
        $query = "DELETE FROM transitions WHERE transition_id={$_POST['transition_id']} LIMIT 1";
        $r = mysqli_query($dbc,$query);
        if (mysqli_affected_rows($dbc) == 1) {
            echo '<p>The marker has been deleted.</p>';
        } else {
            echo '<p style="color: red;">Could not delete the marker because;<br>' . mysqli_error($dbc) . '.</p> <p>The query being run was: ' . $query . '</p>';
        }

    } else { // No ID received.

        echo '<p style="color: red;">This page has been accessed in error.</p>';

    } // End of main if

    mysqli_close($dbc);
    include 'footer.php';

?>
