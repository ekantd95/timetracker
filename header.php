<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link type="text/css" rel="stylesheet" href="timetracker.css" />
    <meta name="viewport" content="width=device-width">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title><?php echo $title; ?></title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php"><p>Home</p></a></li><?php
                if (isset($_SESSION['timetracker_user_id'])) {
                    echo "<li><a href='tracker.php'><p>Tracker</p></a></li>
                    <li><a href='past.php'><p>Past</p></a></li>
                    <div class='dropdown-container'>
                        <li><a href='#'><p>Edit</p></a></li>
                        <ul class='dropdown-content'>
                            <li><a href='add_delete.php'><p>Edit/Delete</p></a></li>
                            <li><a href='add_marker.php'><p>Add</p></a></li>
                        </ul>
                    </div>
                    <li><a href=\"categories.php\"><p>Categories</p></a></li>
                    <li><a href=\"test.php\"><p>Test</p></a></li>";
                }

                // if session exists and it isn't the logout page
                if ( (isset($_SESSION['timetracker_user_id'])) && (basename($_SERVER['PHP_SELF']) != 'logout.php') ) {

                    ?><div class="dropdown-container right">
                    <li class="right"><a id="customer_fn" href="#"><p><?

                    if (isset($_SESSION['timetracker_first_name'])) {
                        echo $_SESSION['timetracker_first_name'];
                    } else {
                        echo 'first name not set';
                    }

                    ?></p></a></li>
                    <ul class="dropdown-content">
                        <li><a href="logout.php"><p>Logout</p></a></li>
                    </ul>
                    </div><?php

                } else {
                    echo "<li class='right'><a href='login.php'><p>Login</p></a></li>\n
                    <li class='right'><a href='register.php'><p>Register</p></a></li>";
                }

                ?>

            </ul>
        </nav>
    </header>
    <div class="page_container">
    <!-- begin page specific content -->
