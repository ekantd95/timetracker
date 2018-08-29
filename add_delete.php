<?php
    session_start();
    $title = 'Edit/Delete';
    include 'header.php';
    include 'login_functions.inc.php';
    if (!isset($_SESSION['timetracker_user_id'])) {
        redirect_user();
    }

    include 'functions.php';
    ?>
    <!-- form -->
    <!-- end of form -->
    <?php

    // hook up database
    require ('timetracker_connect.php');

    if (isset($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
    } else {
        $pageno = 1;
    }
    $no_of_records_per_page = 20;
    $offset = ($pageno-1) * $no_of_records_per_page;

    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        die();
    }

    $total_pages_sql = "SELECT COUNT(*) from transitions where user_id={$_SESSION['timetracker_user_id']}";
    $r = mysqli_query($dbc, $total_pages_sql);
    $total_rows = mysqli_fetch_array($r)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);

    $sql = "SELECT event_name, category, start_event, time_saved, UNIX_TIMESTAMP(time_saved) as tim, transition_id
    from transitions
    where user_id={$_SESSION['timetracker_user_id']}
    order by time_saved
    desc limit $offset, $no_of_records_per_page";
    $res_data = mysqli_query($dbc,$sql);
    echo "<div id=\"edit_delete_page_container\" class=\"page_container\"><!-- edit/delete page container -->";

    ?><table id="add_delete_table" class="results_table">
        <tr>
            <th></th>
            <th></th>
            <th>Name</th>
            <th>Category</th>
            <th>Start?</th>
            <th>Length(sec)</th>
            <th>Time saved</th>
        </tr><?
        $rows = [];
        while($row = mysqli_fetch_array($res_data,MYSQLI_ASSOC)) {
            $rows[] = $row;
        }

    for ($i = 0; $i < count($rows); $i++) { // for each row
        // calculate length if it's not a start event
        if (!isset($rows[$i]['start_event'])) {
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
                        $event_length = '-';
                    }
                } else { // query didn't work
                    echo 'just older query didn\'t work';
                    echo 'query: ' . $q2;
                    echo 'errors: ' . mysqli_error($dbc);
                }
            } else { // you have the data for every other row
                $event_length = $rows[$i]['tim'] - $rows[$i + 1]['tim'];
            }
        } else { // if it is a start event
            $event_length = '-';
        }

        if ($event_length !== '-') {
            $event_length = format_time($event_length);
        }
            ?><tr>
            <td><a href="edit_marker.php?transition_id=<?php echo $rows[$i]['transition_id']; ?>">Edit</a></td>
            <td><a href="delete_marker.php?transition_id=<?php echo $rows[$i]['transition_id']; ?>">Remove</a></td>
            <td><?php echo $rows[$i]['event_name']; ?></td>
            <td><?php echo $rows[$i]['category']; ?></td>
            <td><?php if (isset($rows[$i]['start_event'])) { echo 'Yes'; }; ?></td>
            <td><?php echo $event_length; ?></td>
            <td><?php echo $rows[$i]['time_saved']?> </td></tr>

    <?php } // for loop for each row

    ?></table><?

    mysqli_close($dbc);

?><br /><ul class="pagination">
    <li><a href="?pageno=1">First</a></li>
    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
    </li>
    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
    </li>
    <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
</ul><?

echo "</div><!-- end of edit/delete page_container -->";
include 'footer.php';
