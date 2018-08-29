<?php
// session_start();
// require 'timetracker_connect.php';

function format_time($time_in_seconds) {

    if ($time_in_seconds < 60) { // display in seconds

        $formatted_time = $time_in_seconds . ' sec';

    } else if ($time_in_seconds < 3600) { // display in minutes and seconds

        $remainder_seconds = $time_in_seconds % 60;
        $minutes = ($time_in_seconds - $remainder_seconds) / 60;

        $formatted_time = $minutes . ' min, '. $remainder_seconds . ' sec';

    } else { // display in hours minutes and seconds

        $seconds = $time_in_seconds % 3600;
        $hours = ($time_in_seconds - $seconds) / 3600;
        $remainder_seconds = $seconds % 60;
        $minutes = ($seconds - $remainder_seconds) / 60;

        $formatted_time = $hours . ' hrs, ' . $minutes . ' min, ' . $remainder_seconds . ' sec ';

    }

    return $formatted_time;

}

function selected($option, $category) {
    if ($option == $category) {
        echo 'selected';
    }
}

function mysqliresult_to_array($r) {
    while ($row = mysqli_fetch_array($r)) {
        $result[] = $row;
    }
    return $result;
}

function color_key($dbc) {
    $key = '';
    $key .= "<input type=\"hidden\" id=\"colorkey\" name=\"colorkey\" value=\"";

    $q = "SELECT category_name, color from categories
    where user_id={$_SESSION['timetracker_user_id']} order by category_name";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        if (mysqli_num_rows($r) > 0) {
            $i = 0;
            $datastring = '';
            while ($row = mysqli_fetch_array($r)) {
                if ($i == 0) {
                    $current_set = '';
                } else {
                    $current_set = ';';
                }
                $current_set .= $row['category_name'] . ',' . $row['color'];
                $datastring .= $current_set;
                $i++;
            } // end of while
            $key .= $datastring;
        } else { // no categories set
            $key .= "blank";
        }
    } else { // query didn't work
        $key .= "query didn't work";
    }
    $key .= "\" />";
    return $key;
}

function categories_select($dbc) {
    $q = "SELECT * from categories
    where user_id={$_SESSION['timetracker_user_id']}
    and active=1
    order by category_name;";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        $cat_string ='';
        if (mysqli_num_rows($r) > 0) { // if there are categories display them
            $cat_string .= "<select id=\"category\" name=\"category\"><option selected disabled value=\"none\">None</option>";
            while ($row = mysqli_fetch_array($r)) {
                $cat_string .= "<option value=\"{$row['category_name']}\">{$row['category_name']}</option>";
            }
            $cat_string .= "</select>";
        } else { // there were no categories
            $cat_string .= "<a href=\"categories.php\" class=\"cat\">Add a category!</a>";
        }
    } else {
        $cat_string .= 'query failed';
    } // query failed
    return $cat_string;
}
