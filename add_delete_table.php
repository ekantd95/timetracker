<?php

// hook up database
require ('timetracker_connect.php');
include ('functions.php');

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

$sql = "SELECT event_name, category, start_event, time_saved, UNIX_TIMESTAMP(time_saved) as tim
FROM transitions
ORDER BY time_saved
DESC LIMIT $offset, $no_of_records_per_page";
$res_data = mysqli_query($dbc,$sql);

?><table id="add_delete_table">
    <th>
        <th>spittle</th>
        <th>spittle</th>
        <th>Event Name</th>
        <th>Category</th>
        <th>Start?</th>
        <th>Length (sec)</th>
        <th>Time Saved</th>
    </th><?

    $rows = [];
    while($row = mysqli_fetch_array($res_data,MYSQLI_ASSOC)) {
        $rows[] = $row;
    }

for ($i = 0; $i < count($rows); $i++) {
    if ($i == count($rows) - 1) { // on last row query for the row just older to calculate length
        $q2 = "SELECT time_saved, UNIX_TIMESTAMP(time_saved) as tim
        from transitions
        where UNIX_TIMESTAMP(time_saved) < {$rows[$i]['tim']}
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
        }
    } else { // you have the data for every other row
        $event_length = $rows[$i]['tim'] - $rows[$i + 1]['tim'];
    }

    if ($event_length !== '-') {
        $event_length = format_time($event_length);
    }
        ?><tr>
        <td><a href="edit_marker.php?transition_id=<?php echo $row['transition_id']; ?>">Edit</a></td>
        <td><a href="delete_marker.php?transition_id=<?php echo $row['transition_id']; ?>">Remove</a></td>
        <td><?php echo $row[$i]['event_name']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td><?php if (isset($row['start_event'])) { echo 'Yes'; }; ?></td>
        <td><?php echo $event_length; ?></td>
        <td><?php echo $row['time_saved']?> </td></tr>

<?php } // for loop for each row

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
