<?php
    session_start();
    include 'header.php';
    include 'functions.php';
    //hook up database
    require ('timetracker_connect.php');

    mysqli_set_charset($dbc, 'utf8');

    // if link was clicked to edit marker
    if (isset($_GET['transition_id']) AND is_numeric($_GET['transition_id'])) {

        $query = "SELECT event_name, category
        FROM transitions
        WHERE transition_id={$_GET['transition_id']}";
        if ($r = mysqli_query($dbc,$query)) {
            $row = mysqli_fetch_array($r);
            $category = $row['category'];

            ?><form action="edit_marker.php" method="post">
            <p>Event Name: <input type="text" name="event_name" size="40" maxsize="100" value="<?php  echo htmlentities($row['event_name']) ?>" /></p>
            <p>Category:

            <select id="category" name="category">
                <option value="Work" <? selected("Work", $category) ?> >Work</option>
                <option value="Chill" <? selected("Chill", $category) ?> >Chill</option>
                <option value="WebDev" <? selected("WebDev", $category) ?> >WebDev</option>
                <option value="Late" <? selected("Late", $category) ?> >Late</option>
            </select>

            </p>
            <input type="hidden" name="transition_id" value="<?php echo $_GET['transition_id']; ?>">
            <input type="submit" name="submit" value="Update this Entry!">
        </form><?php

        } else { // If the query failed
            echo '<p style="color: red;">Could not retrieve the transition because: <br>' . mysqli_error($dbc) . '.</p>
            <p>The query being run was: ' . $query . '</p>';
        }

    // if confirmation was clicked ie. save the edited maker
    } elseif (isset($_POST['transition_id']) AND is_numeric($_POST['transition_id'])) {
        $problem = FALSE;
        if(!empty($_POST['event_name']) AND !empty($_POST['category'])) {
            $event_name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['event_name'])));
            $category = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['category'])));
        } else {
            echo '<p style="color: red;">Please submit both a title and an entry.<?p>';
            $problem = TRUE;
        }
        if (!$problem) {
            $query = "UPDATE transitions
            SET event_name='$event_name', category = '$category'
            WHERE transition_id={$_POST['transition_id']}";
            $r = mysqli_query($dbc,$query);
            if (mysqli_affected_rows($dbc) == 1) {
            echo '<p>The blog entry has been updated.</p>';
            } else { // Table wasn't updated
                echo '<p style="color: red;">Could not update the entry because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
            }
        } else { //
            echo '<p style="color: red;">This page has been accessed in error.</p>';
        }
    } else {
        echo '<p>This page was accessed in error</p>';
    }
    mysqli_close($dbc);
    include 'footer.php';
    ?>
