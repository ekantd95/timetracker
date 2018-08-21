<?php
    session_start();
    include 'header.php';
    include 'functions.php';
    $today = date('Y-m-d');
    $one_week_ago = date('Y-m-d', time() - (7 * 24 * 60 * 60));
    $now = date('H-i-s');


    ?>
    <form method="post" action="test.php">

        <!-- transition name -->
        <label for="name">Add Marker:</label><input id="event_name" type="text" name="name">
        <!-- transition type -->
        <select id="category" name="category">
            <option value="Work">Work</option>
            <option value="Chill">Chill</option>
            <option value="WebDev">WebDev</option>
            <option value="Late">Late</option>
        </select>
        <!-- start and stop -->
        <label for="start">Start?</label> <input name="start" id="start" type="checkbox" />
        <!-- day and time -->
        <label for="day">Day</label> <input name="day" id="day" type="date" min="<?php echo $one_week_ago; ?>" max="<?php echo $today; ?>"/>
        <label for="time">Time</label> <input type="time" id="time" name="time" min="0:00" max="24:00" required /><br /><br />

        <input type="submit" value="Submit that shit" />
    </form>
    <?

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo 'Name: ' . $_POST['name'] . '<br />' .
        'Category: ' . $_POST['category'] . '<br />' .
        'Start? :' . $_POST['start'] . '<br />' .
        'Day: ' . $_POST['day'] . '<br />' .
        'Time: ' . $_POST['time'] . '<br />';
    }





    include 'footer.php';
?>
