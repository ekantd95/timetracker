<?php
    session_start();
    include 'header.php';
    include 'functions.php';
    // hook up database
    require 'timetracker_connect.php';


    if (isset($_GET['category_id']) AND is_numeric($_GET['category_id'])){ // first time clicked

        $query = "SELECT category_name, color
        FROM categories
        WHERE category_id={$_GET['category_id']}
        and user_id={$_SESSION['timetracker_user_id']}";
        if ($r = mysqli_query($dbc,$query)) {

            $row = mysqli_fetch_array($r);
            echo '<form action="delete_category.php" method="post">
            <p>Are you sure you want to delete this category?</p>
            <p>Category:<h3>' . $row['category_name'] . '</h3></p>
            <input type="hidden" name="category_id" value="' . $_GET['category_id'] . '">
            <input type="submit" name="submit" value="Delete this category!"></p>
            </form>';

        } else { // Couldn't return the query
            echo '<p style="color: red;">Could not retrieve the category because:<br>' . mysqli_error($dbc) . '.</p> <p>The query being run was: ' . $query . '</p>';
        }

    } elseif (isset($_POST['category_id']) AND is_numeric($_POST['category_id'])) { // confirmed

         // Handle the form
        $query = "DELETE FROM categories WHERE category_id={$_POST['category_id']} LIMIT 1";
        $r = mysqli_query($dbc,$query);
        if (mysqli_affected_rows($dbc) == 1) {
            echo '<p>The category has been deleted.</p>';
        } else {
            echo '<p style="color: red;">Could not delete the category because;<br>' . mysqli_error($dbc) . '.</p> <p>The query being run was: ' . $query . '</p>';
        }

    } else { // No ID received.

        echo '<p style="color: red;">This page has been accessed in error.</p>';

    } // End of main if

    mysqli_close($dbc);
    include 'footer.php';

?>
