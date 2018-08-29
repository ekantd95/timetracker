<?php
    session_start();
    include 'login_functions.inc.php';
    if (!isset($_SESSION['timetracker_user_id'])) {
        redirect_user();
    }
    $title = 'Tracker';
    include 'header.php';
    include 'functions.php';
    require 'timetracker_connect.php';

?>

    <div id="tracker_page_container" class="page_container">

        <!-- current date -->
        <br />
        <h4 class="current_date"><?php echo date('D, jS') . ' of ' .  date('F Y'); ?></h4>

        <!-- scale inputs -->
        <label for="9">8-17</label><input class="scale_input" id="9" name="scale" type="radio" />&nbsp;
        <label for="24">0-24</label><input class="scale_input" id="24" name="scale" type="radio" checked/>&nbsp;
        <label for="1">Past hour</label><input class="scale_input" id="1" name="scale" type="radio" />

        <!-- canvas -->
        <canvas id="main_canvas"></canvas>

        <!-- form -->

            <!-- marker name -->
            <label for="name">Add Marker:</label><input id="event_name" type="text" name="name">
            <!-- marker category -->
            <? echo categories_select($dbc); ?>
            <!-- start event? -->
            <label for="start">Start?</label><input id="start" type="checkbox" />
            <!-- color key for plotting -->
            <?php echo color_key($dbc); ?>
            <!-- submit -->
            <button id="submit" type="button">Submit</button>

        <!-- end of form -->

        <!-- warning element -->
        <p id="warning"></p>
        <!-- output element for table -->
        <p id="output"></p>

    </div><!-- page_container -->

<!-- <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous">
</script>    		 -->
<script src="ajax.js"></script>
<script src="functions.js"></script>
<script src="timetracker.js"></script>

<?php include 'footer.php'; ?>
