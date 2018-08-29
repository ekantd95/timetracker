<?php
session_start();
// hook up
require 'timetracker_connect.php';
$q = "SELECT category_name from categories
where active=1
and user_id={$_SESSION['timetracker_user_id']}";
$r = mysqli_query($dbc, $q);
if ($r) {
    $data = [];
    while($row = mysqli_fetch_array($r)) {
        $data[] = $row['category_name'];
    }
    echo json_encode($data);
} else {
    echo 'query didn\'t work';
}
