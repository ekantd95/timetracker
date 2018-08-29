<?php
    session_start();
    include 'header.php';
    include 'functions.php';
    //hook up database
    require ('timetracker_connect.php');

    mysqli_set_charset($dbc, 'utf8');

    // if link was clicked to edit marker
    if (isset($_GET['category_id']) AND is_numeric($_GET['category_id'])) {

        $query = "SELECT category_name, color
        FROM categories
        WHERE category_id={$_GET['category_id']}
        and user_id={$_SESSION['timetracker_user_id']}";
        if ($r = mysqli_query($dbc,$query)) {
            $row_1 = mysqli_fetch_array($r);

            ?><form action="edit_category.php" method="post">
            <p>Category: <input type="text" name="category_name" size="40" maxsize="100" value="<?php  echo htmlentities($row_1['category_name']); ?>" /></p>
            <p>Color: <input type="color" name="color" value="<?php echo htmlentities($row_1['color']); ?>" /></p>

            </p>
            <input type="hidden" name="category_id" value="<?php echo $_GET['category_id']; ?>">
            <input type="submit" name="submit" value="Update this Entry!">
        </form><?php

        } else { // If the query failed
            echo '<p style="color: red;">Could not retrieve the category because: <br>' . mysqli_error($dbc) . '.</p>
            <p>The query being run was: ' . $query . '</p>';
        }

    // if confirmation was clicked ie. save the edited maker
    } elseif (isset($_POST['category_id']) AND is_numeric($_POST['category_id'])) {
        $problem = FALSE;
        if(!empty($_POST['category_name']) AND !empty($_POST['color'])) {
            $category_name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['category_name'])));
            $color = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['color'])));
        } else {
            echo '<p style="color: red;">Please submit both a title and an entry.<?p>';
            $problem = TRUE;
        }
        if (!$problem) {
            $query = "UPDATE categories
            SET category_name='$category_name', color = '$color'
            WHERE category_id={$_POST['category_id']}";
            $r = mysqli_query($dbc,$query);
            if (mysqli_affected_rows($dbc) == 1) {
            echo '<p>The category has been updated.</p>';
            } else { // Table wasn't updated
                echo '<p style="color: red;">Could not update the category because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
            }
        } else { //
            echo '<p style="color: red;">This page has been accessed in error.</p>';
        }
    } else {
        echo '<p>This page was accessed in error</p>';
    }
    mysqli_close($dbc);
    include 'footer.php';
    ?>
