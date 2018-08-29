<?php
    session_start();
    require 'timetracker_connect.php';
    include 'login_functions.inc.php';
    if (!isset($_SESSION['timetracker_user_id'])) {
        redirect_user();
    }
    include 'header.php';
    include 'functions.php';
?>

<div id="past_page_container" class="page_container">

    <!-- <select id="past_select">
        <option value="7d">Last 7 days</option>
        <option value="4w">Last 4 weeks</option>
    </select> -->
    <select>
        <option value="Past 7 Days">Past 7 Days</option>
    </select> More coming soon<br /><br />
    <label for="9">8-17</label><input class="scale_input" id="9" name="scale" type="radio" />&nbsp;
    <label for="24">0-24</label><input class="scale_input" id="24" name="scale" type="radio" checked/>&nbsp;
    <?php echo color_key($dbc); ?>



        <?php
            for ($i = 0; $i < 7; $i++) { ?>
                <p class="current_date"><?php echo date('D, jS', time() - (24 * 60 * 60 * $i)) . ' of ' .  date('F Y', time() - (24 * 60 * 60 * $i)); ?></p>
                <canvas class="sevenD_canvas" id="<?php echo $i; ?>"></canvas>
            <? }


        ?>
    </div>

</div><!-- end of .page_container -->

<script src="functions.js"></script>
<script src="past.js"></script>

<?php include 'footer.php'; ?>
