<?php
session_start();
// hook up
require ('timetracker_connect.php');
include 'login_functions.inc.php';
$title = 'Categories';
if (!isset($_SESSION['timetracker_user_id'])) {
    redirect_user();
}

include 'header.php';


?><p>Enter the categories that you want to be able to choose for an event</p>
<form action="categories.php" method="post" id="categories_form">
    <input id="category" name="category" type="text" maxlength="20" />
    <input id="color" name="color" type="color" value="#ffffff" />
    <input id="turnin" type="button" value="Submit" />
</form>
<p id="error"></p>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle if submitted
    if (isset($_POST['category']) && isset($_POST['color'])) {

        $category = mysqli_real_escape_string($dbc, trim($_POST['category']));
        $color = mysqli_real_escape_string($dbc, trim($_POST['color']));

        $q = "INSERT into categories (category_name, color, user_id, time_saved,active)
        values ('{$category}','{$color}',{$_SESSION['timetracker_user_id']}, NOW(),1);";
        $r = mysqli_query($dbc, $q);
        if ($r) {
            if (mysqli_affected_rows($dbc) == 1) {
                // echo '<p>Row inserted successfully</p>';

            } else {
                echo 'one row was not affected';
            }
        } else { // Query failed
            echo 'Query failed';
            echo $q;
        }
    } // if values are set
} // if submitted

// show table of categories
$q = "SELECT * from categories
where active='1' and
user_id={$_SESSION['timetracker_user_id']}
order by time_saved asc";
$r = mysqli_query($dbc, $q);
if ($r) {
    if (mysqli_num_rows($r) > 0) {
        echo "<table id=\"categories\" class=\"results_table\">
            <tr>
                <th></th>
                <th></th>
                <th>Category</th>
                <th>Color</th>
            </tr>";
            while ($row = mysqli_fetch_array($r)) {
                echo "<tr>
                    <td><a href=\"edit_category.php?category_id={$row['category_id']}\">Edit</a></td>
                    <td><a href=\"delete_category.php?category_id={$row['category_id']}\">Delete</a></td>
                    <td><p>{$row['category_name']}</p></td>
                    <td id=\"category_color\" style=\"background-color: {$row['color']}\"></td>
                </tr>";
            }
        echo '</table>';
    } else {
        echo 'No categories yet.';
    }
} else { // query didn't work
    echo 'table query failed';
    echo $q;
}

echo "<script src=\"categories.js\"></script>";
include 'footer.php';
