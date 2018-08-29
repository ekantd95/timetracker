<?php
session_start();
include 'login_functions.inc.php';
include 'functions.php';
if (!isset($_SESSION['timetracker_user_id'])) {
    redirect_user();
}
require('timetracker_connect.php');

$title = 'Add Markers';
include 'header.php';
$today = date('Y-m-d');
$one_week_ago = date('Y-m-d', time() - (7 * 24 * 60 * 60));
$now = date('H:i:s');

?>
<div class="page_container">
<!-- form -->
<p id="add_marker_errors"></p>
<form id="add_marker_form" name="add_marker_form" method="post" action="add_marker.php">

<?

for ($i = 0; $i < 10; $i++) { ?>
    <div class="entry" id="entry_<? echo $i; ?>">

        <!-- transition name -->
        <label for="name">Add Marker:</label><input id="event_name_<? echo $i; ?>" type="text" name="event_name_<? echo $i; ?>" novalidate <?php
            if (isset($_POST['event_name_' . $i])) {
                echo "value='{$_POST['event_name_' . $i]}'";
            }
          ?> /><!-- end of event_name input -->
        <!-- transition type -->
        <?php

        // query for categories for dropdown
        $q = "SELECT * from categories
        where user_id={$_SESSION['timetracker_user_id']}
        and active=1
        order by category_name;";
        $r = mysqli_query($dbc, $q);
        if ($r) {
            $cat_string ='';
            if (mysqli_num_rows($r) > 0) { // if there are categories display them
                echo  "<select id=\"category_{$i}\" name=\"category_{$i}\"><option selected disabled value='none'>None</option>";
                // print each category
                while ($row = mysqli_fetch_array($r)) {
                    if ($_POST['category_' . $i] == $row['category_name']) {
                        echo "<option value='{$row['category_name']}' selected>{$row['category_name']}</option>";
                    } else {
                        echo "<option value='{$row['category_name']}'>{$row['category_name']}</option>";
                    }
                } // end of while
                echo "</select>";
            } else { // there were no categories
                echo "<a href='categories.php' class='cat'>Add a category!</a>";
            }
        } else {
            echo 'query failed';
        } // query failed

        // print out checkbox
        echo " <label for='start'>Start?</label> <input name='start_{$i}' id='start_${i}' ";
        if (isset($_POST['start_' . $i])) {
            echo 'checked ';
        };
        echo "type='checkbox'  novalidate/><!-- end of start event checkbox -->"; // end of checkbox

        // print day input
        echo " <label for='day'>Day </label><input name='day_{$i}' id='day_{$i}' type='date' min='$one_week_ago' max='$today' novalidate ";
        if (isset($_POST['day_' . $i])) {
            echo "value='{$_POST['day_' . $i]}'";
        }
        echo "/>";
        // print time input
        echo " <label for='time'>Time </label><input name='time_{$i}' id='time_{$i}' type='time'";
        if (isset($_POST['time_' . $i])) {
            echo "value='{$_POST['time_' . $i]}'";
        };
        echo " />";

        // print value time stamp value to submit
        echo "<input type='hidden' class='stamp' name='stamp_{$i}' id='stamp_{$i}' value='' />";
        /// print checks and crosses
        echo "<img src='check.svg' class='check up' id='check_{$i}'/>";
        echo "<img src='cross.svg' class='cross' id='cross_{$i}' />";
        // print error p
        echo " <p class='add_marker_error' id='error_{$i}'></p>"; ?>
        <br /><br />
    </div>
<?php } // end of for loop ?>
<input id="turnin" type="button" value="Submit" />
<p id='macro' class="error"></p>

</form>
<!-- end of form -->

<?php

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
            if (isset($_POST['category_' . $i])) {
                $current_set .= '\'' . mysqli_real_escape_string($dbc, trim($_POST['category_' . $i])) . '\',';
            } else {
                $current_set .= '\'none\',';
            }
            // start event
            if (isset($_POST['start_' . $i])) {
                // check if previous row in table is a start event
                // also need to
                $current_set .= '1,';
            } else {
                $current_set .= 'null,';
            }

            // check to see that marker with this timestamp doesn't already exist
            $q = "SELECT * from transitions
            where UNIX_TIMESTAMP(time_saved)={$_POST['stamp_' . $i]}
            and user_id={$_SESSION['timetracker_user_id']}";
            $r = mysqli_query($dbc, $q);
            if ($r) {
                if (mysqli_num_rows($r) > 0) {
                    $errors .= "timestamp already exists at the time specified for {$_POST['event_name_' . $i]}<br />";
                } else {
                    $current_set .= 'FROM_UNIXTIME(' . mysqli_real_escape_string($dbc, trim($_POST['stamp_' . $i])) . '),';
                    $current_set .= $_SESSION['timetracker_user_id'] . ')';
                }
            }

            $values .= $current_set;

        } // end of as long as post_time isn't ""
    }

    if (strlen($errors) == 0) {
        // compile query to submit it all at once;
        $q = "INSERT into transitions (event_name, category, start_event, time_saved, user_id)
        values $values";

        $r = mysqli_query($dbc, $q);

        if ($r) {
            echo "Successfully submitted";
        } else {
            echo mysqli_error($dbc);
        }

    } else { // print errors
        echo '<p class="error">Errors:<br />' . $errors . '</p>';
    }

} // if server request method is post







echo "</div><!-- page container -->";
echo "<script src=\"add_marker.js\"></script>";
echo "<script src=\"functions.js\"></script>";
include 'footer.php' ?>
