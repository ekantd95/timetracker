<?php

    // hook up database
    require ('timetracker_connect.php');


    if (isset($_POST['stamp'])) {

        $q = "SELECT *
        from transitions
        where UNIX_TIMESTAMP(time_saved)={$_POST['stamp']};";
        

        $r = mysqli_query($dbc, $q);
        if ($r) {
            if (mysqli_num_rows($r) > 0) {
                echo 'yes';
            } else {
                echo 'no';
            }
        } else { // query failed
            echo mysqli_error($dbc);
            echo 'failed';
        }

    }
