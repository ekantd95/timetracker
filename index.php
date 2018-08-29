<?php
session_start();
include 'header.php';
 ?>

<div id="index_page_container" class="page_container">
    <div id="welcome_text"><h1 id="title">TimeMarker.</h1><br />Hi, this software is meant to track your time NOT plan it. All the time tracking applications that I've used have made logging events cumbersome and time consuming. My goal was to create an application that was simple and quick to log with. Here you enter 'markers' and not events into your timeline. There are two types of markers: start and end. You begin by entering a start marker and everytime you transition from one task to another you add the marker with the name of the task you <em>just finished</em>.You don't have to worry about stopping the recording, just remember to insert a start event next time you want to start recording. All markers are of course, editable. Markers are stored directly in the database so you don't have to worry about closing the browser window. Register to begin.</div>
</div>

<?php include 'footer.php'; ?>
