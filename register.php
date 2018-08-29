<?php
session_start();
$title = 'Register';
include 'header.php';
include 'functions.php';
$script = 'login.js';
?>
<div id="registration_page_container" class="page_container">
<div class="form_container">
    <h1>Register</h1><br />
    <form class="site_form" id="registration_form" action="register.php" method="post">
        <p>First Name:<img src="check.svg" class="up symbol" id="first_name_check" /><img src="cross.svg" class="symbol" id="first_name_cross" />
            <br />
            <input type="text" id="first_name" name="first_name" size="15" maxlength="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" />
        </p>
        <p>Last Name:<img src="check.svg" class="up" id="last_name_check" /><img src="cross.svg" id="last_name_cross" />
            <br />
            <input type="text" id="last_name" name="last_name" size="40" maxlength="20" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>"/>
        </p>
        <p>Username:<img src="check.svg" class="up" id="username_check" /><img src="cross.svg" id="username_cross" />
            <br />
            <input type="text" id="username" name="username" size="20" maxlength="60" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" />
        </p>
        <p>Password:<img src="check.svg" class="up" id="pass1_check" /><img src="cross.svg" id="pass1_cross" />
            <br />
            <input type="password" id="pass1" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" />
        </p>
        <p>Confirm Password:<img src="check.svg" class="up" id="pass2_check" /><img src="cross.svg" id="pass2_cross" />
            <br />
            <input type="password" id="pass2" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" />
        </p><br /><br />
        <p><input type="submit" id="register" name="submit" value="Register" disabled="true"/></p>
    </form>
<?php

// validate form if it was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// connect
require ('timetracker_connect.php');

$errors = array();

// Validate first name
if (empty($_POST['first_name'])) {
$errors[] = 'You forgot to enter your first name.';
} else {
$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
}

// Validate last_name
if (empty($_POST['last_name'])) {
$errors[] = 'You forgot to enter your last name.';
} else {
$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
}

// Validate username
if (!empty($_POST['username'])) {

$e = mysqli_real_escape_string($dbc, trim($_POST['username']));

// make sure username doesn't already exist
$eq = "SELECT user_id FROM users WHERE username='$e'";
$r = mysqli_query($dbc, $eq);
if (mysqli_num_rows($r) > 0) {
$errors[] = 'Selected username alredy exists';
}

} else { // empty field
$errors[] = 'You forgot to enter your username.';
}


// Validate the passwords
if (!empty($_POST['pass1'])) {
if ($_POST['pass1'] != $_POST['pass2']) {
$errors[] = 'Your password did not match the confirmed password.';
} else {
$p = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
}
} else {
$errors[] = 'You forgot to enter your password.';
}

if (empty($errors)) {


// Compile query
$q = "INSERT INTO users (first_name, last_name, username, password, registration_date)
VALUES ('$fn', '$ln', '$e', SHA1('$p'), NOW() )";

// Submit/set equal to $r
$r = @mysqli_query($dbc, $q);


if ($r) {
echo '<div id="registered_page_container"><p>You are now registered!<br />Please login to continue.</p></div>';
} else {
echo '<h1>System Error</h1>
<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
} // End of if ($r) IF

mysqli_close($dbc);
include('footer.php');
exit();

} else { // if any errors popped up
echo '<p>Error!</p>
<p class="error">The following error(s) occured:<br />';
foreach ($errors as $msg) {
echo " - $msg<br />\n";
}
echo '</p><p><br /></p>';
} // End of empty($errors) if

mysqli_close($dbc);

}
echo '</div><!-- .form_container -->';
echo '</div><!-- end of page_container -->';
include ('footer.php'); ?>
