<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link type="text/css" rel="stylesheet" href="timetracker.css" />
    <meta name="viewport" content="width=device-width">
    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i" rel="stylesheet"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <title>Time Tracker muthafuckas today</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php"><p>Home</p></a></li>
                <li><a href="tracker.php"><p>Tracker</p></a></li>
                <li><a href="past.php"><p>Past</p></a></li>
                <div class="dropdown-container">
                    <li><a href="#"><p>Edit</p></a></li>
                    <ul class="dropdown-content">
                        <li><a href="add_delete.php"><p>Edit/Delete</p></a></li>
                        <li><a href="add_marker.php"><p>Add</p></a></li>
                    </ul>
                </div>
                <li><a href="test.php"><p>Test</p></a></li>

                <?php

                // if session exists and it isn't the logout page
                if ( (isset($_SESSION['user_id'])) && (basename($_SERVER['PHP_SELF']) != 'logout.php') ) {

                    ?><div class="dropdown-container right">-
                    <li class="right"><a id="customer_fn" href="#"><p><?

                    if (isset($_SESSION['first_name'])) {
                        echo $_SESSION['first_name'];
                    } else {
                        echo 'first name not set';
                    }

                    ?></p></a></li>
                    <ul class="dropdown-content">
                        <li><a href="logout.php"><p>Logout</p></a></li>
                    </ul>
                    </div><?
                    
                } else {
                    echo "<li class=\"right\"><a href=\"login.php\"><p>Login</p></a></li>\n
                    <li class=\"right\"><a href=\"register.php\"><p>Register</p></a></li>";
                }

                ?>

            </ul>
        </nav>
    </header>
    <div class="page_container">
    <!-- begin page specific content -->
