<? # logout.php
  session_start();
  if (!isset($_SESSION['timetracker_user_id'])) {
    require ('login_functions.inc.php');
    redirect_user();
  } else {
    $_SESSION = array();
    session_destroy();
    setcookie ('PHPSESSID', '', time()-3600, '/', '', 0, 0);
  }

  $page_title = 'Logged Out!';
  include ('header.php');
  ?>
    <div id="loggedout_page_container" class="page_container">
      <div id="loggedout" class="form_container">
        <p>You've been logged out!</p>
      </div>
    </div>
  <?
  include ('footer.php');
?>
