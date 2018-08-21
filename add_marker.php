<?php

session_start();
include 'header.php';
$today = date('Y-m-d');
$one_week_ago = date('Y-m-d', time() - (7 * 24 * 60 * 60));
$now = date('H-i-s');

?>

<!-- form -->
<form id="add_delete_add" method="post" action="time_save.php">

<? for ($i = 0; $i < 10; $i++) { ?>
    <div class="entry" id="entry_<? echo $i; ?>">

        <!-- transition name -->
        <label for="name">Add Marker:</label><input id="event_name_<? echo $i; ?>" type="text" name="name">
        <!-- transition type -->
        <select id="category_<? echo $i; ?>">
            <option value="Work">Work</option>
            <option value="Chill">Chill</option>
            <option value="WebDev">WebDev</option>
            <option value="Late">Late</option>
        </select>
        <!-- start and stop -->
        <label for="start">Start?</label> <input id="start_<? echo $i; ?>" type="checkbox" />
        <!-- day and time -->
        <label for="day">Day</label> <input id="day_<? echo $i; ?>" type="date" min="<?php echo $one_week_ago; ?>" max="<?php echo $today; ?>"/>
        <label for="time">Time</label> <input type="time" id="time_<? echo $i; ?>" name="time" min="0:00" max="24:00" required /><br /><br />

    </div>
<?php } // end of for loop ?>

<input id="submit" type="submit" value="Submit all" />
</form>
<!-- end of form -->

<?php

// handle form if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {




}








echo "<script src=\"add_marker.js\"></script>";
include 'footer.php' ?>
