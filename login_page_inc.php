<?php

$page_title = 'Login';
include 'header.php';

if ( isset($errors) && !empty($errors) ) {
    echo '<h1>Error!</h1>
    <p class="error">The following error(s) occurred:<br />';
    foreach ($errors as $msg) {
        echo " - $msg<br />\n";
    }
    echo '</p><p>Please try again.</p>';
}
