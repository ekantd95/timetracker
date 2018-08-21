<?php
    session_start();
    include 'header.php';
    include 'functions.php';
    ?>
    <!-- form -->
    <form id="add_delete_add" method="post" action="time_save.php">
        <!-- transition name -->
        <label for="name">Add Marker:</label><input id="event_name" type="text" name="name">
        <!-- transition type -->
        <select id="category">
            <option value="Work">Work</option>
            <option value="Chill">Chill</option>
            <option value="WebDev">WebDev</option>
            <option value="Late">Late</option>
        </select>
        <!-- start and stop -->
        <label for="start">Start?</label><input id="start" type="checkbox" />
        <!-- submit -->
        <label for="day">Day</label><input id="day" class="time" type="date" />
        <label for="hour">Hour</label><input id="hour" class="time" type="number" />
        <label for="minute">Minute</label><input id="minute" class="time" type="number" />
        <label for="second">Second</label><input id="second" class="time" type="number" />
        <input id="submit" type="submit" value="Submit!" />
    </form>
    <!-- end of form -->
    <?php

    // hook up database
    require ('timetracker_connect.php');

    if (isset($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
    } else {
        $pageno = 1;
    }
    $no_of_records_per_page = 25;
    $offset = ($pageno-1) * $no_of_records_per_page;

    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        die();
    }

    $total_pages_sql = "SELECT COUNT(*) FROM transitions";
    $r = mysqli_query($dbc, $total_pages_sql);
    $total_rows = mysqli_fetch_array($r)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);

    $sql = "SELECT * FROM transitions ORDER BY time_saved DESC LIMIT $offset, $no_of_records_per_page";
    $res_data = mysqli_query($dbc,$sql);

    ?><table id="add_delete_table">
        <tr>
            <th>Edit</th>
            <th>Remove</th>
            <th>Event Name</th>
            <th>Category</th>
            <th>Start?</th>
            <th>Length (sec)</th>
            <th>Time Saved</th>
        </tr><?

    while($row = mysqli_fetch_array($res_data, MYSQLI_ASSOC)){
            $formatted_length = format_time($row['length']);
            ?><tr>
            <td><a href="edit_marker.php?transition_id=<?php echo $row['transition_id']; ?>">Edit</td>
            <td><a href="delete_marker.php?transition_id=<?php echo $row['transition_id']; ?>">Remove</td>
            <td><?php echo $row['event_name']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><?php if (isset($row['start_event'])) { echo 'Yes'; }; ?></td>
            <td><?php echo $formatted_length; ?></td>
            <td><?php echo $row['time_saved']?> </td></tr>

    <?php }

    ?></table><?

    mysqli_close($dbc);

?><ul class="pagination">
    <li><a href="?pageno=1">First</a></li>
    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
    </li>
    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
    </li>
    <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
</ul><?

include 'footer.php';
