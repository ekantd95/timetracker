<?php

session_start();
include 'header.php';
$today = date('Y-m-d');
$one_week_ago = date('Y-m-d', time() - (7 * 24 * 60 * 60));
$now = date('H-i-s');

?>

<!-- form -->
<p id="add_marker_errors"></p>
<form id="add_marker_form" name="add_marker_form" method="post" action="add_marker.php">

<? for ($i = 0; $i < 10; $i++) { ?>
    <div class="entry" id="entry_<? echo $i; ?>">

        <!-- transition name -->
        <label for="name">Add Marker:</label><input id="event_name_<? echo $i; ?>" type="text" name="event_name_<? echo $i; ?>" novalidate>
        <!-- transition type -->
        <select name="category_<? echo $i; ?>" id="category_<? echo $i; ?>" novalidate>
            <option value="Work">Work</option>
            <option value="Chill">Chill</option>
            <option value="WebDev">WebDev</option>
            <option value="Late">Late</option>
        </select>
        <!-- start and stop -->
        <label for="start">Start?</label> <input name="start_<? echo $i; ?>" id="start_<? echo $i; ?>" type="checkbox"  novalidate/>
        <!-- day and time -->
        <label for="day">Day</label> <input name="_<? echo $i; ?>" id="day_<? echo $i; ?>" type="date" min="<?php echo $one_week_ago; ?>" max="<?php echo $today; ?>" novalidate/>
        <label for="time">Time</label> <input name="time_<? echo $i; ?>" type="time" id="time_<? echo $i; ?>" name="time" min="0:00" max="24:00" novalidate/>
        <input type="hidden" name="stamp_<?php echo $i; ?>" id="stamp_<?php echo $i; ?>" value=" " />
        <img src="check.svg" class="check up" id="check_<?php echo $i; ?>" />
        <img src="cross.svg" class="cross" id="cross_<?php echo $i; ?>" />
        <p class="add_marker_error" id="error_<?php echo $i; ?>"></p>
        <br /><br />

    </div>
<?php } // end of for loop ?>
<input id="turnin" type="button" value="fish" />
<p id='macro' class="error"></p>

</form>
<!-- end of form -->

<?php

require('timetracker_connect.php');

// handle form if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $values = '';
    $errors = '';

    for ($i = 0; $i < 10; $i++) {
        if ($_POST['time_' . $i] !== "") {
            if ($i == 0) {
                $current_set = '(';
            } else {
                $current_set = ',(';
            }

            $current_set .= '\'' . mysqli_real_escape_string($dbc, trim($_POST['event_name_' . $i])) . '\',';
            $current_set .= '\'' . mysqli_real_escape_string($dbc, trim($_POST['category_' . $i])) . '\',';

            if (isset($_POST['start_' . $i])) {
                // check if previous row in table is a start event
                // also need to 
                $current_set .= '1,';
            } else {
                $current_set .= 'null,';
            }

            // check to see that marker with this timestamp doesn't already exist
            $q = "SELECT * from transitions
            where UNIX_TIMESTAMP(time_saved)={$_POST['stamp_' . $i]};";
            $r = mysqli_query($dbc, $q);
            if ($r) {
                if (mysqli_num_rows($r) > 0) {
                    $errors .= 'timestamp already existss.';
                } else {
                    $current_set .= 'FROM_UNIXTIME(' . mysqli_real_escape_string($dbc, trim($_POST['stamp_' . $i])) . '))';
                }
            }

            $values .= $current_set;

        } // end of as long as post_time isn't ""
    }

    if (strlen($errors) == 0) {
        // compile query to submit it all at once;
        $q = "INSERT into transitions (event_name, category, start_event, time_saved)
        values $values";
        echo $q;

        $r = mysqli_query($dbc, $q);

        if ($r) {
            echo "successfully submitted";
        } else {
            echo mysqli_error($dbc);
        }

    } else { // print errors
        echo 'here are the errors: ' . $errors;
    }




} // if server request method is post








echo "<script src=\"add_marker.js\"></script>";
include 'footer.php' ?>
