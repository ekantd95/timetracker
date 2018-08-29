<? # script 12.3 - login.php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require ('login_functions.inc.php');
    require ('timetracker_connect.php');

    // validate
    list ($check, $data) = check_login($dbc, $_POST['username'], $_POST['password']);

    if ($check) {
      // set session data
      session_start();
      $_SESSION['timetracker_user_id'] = $data['user_id'];
      $_SESSION['timetracker_first_name'] = $data['first_name'];
      // Store the HTTP_USER_AGENT
      $_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);
      // redirect

      redirect_user('tracker.php');
    } else {
      $errors = $data;
    }

    mysqli_close($dbc);

  } // server request method conditional

  $title = 'Login';
  include ('header.php');

  ?><div class="page_container" id="login_page_container">
  <div class="form_container" id="form_container">
    <h1>Login</h1><br />
    <form class="site_form" action="login.php" method="post">
      <p>Username:<br /> <input type="text" name="username" size="20" maxlength="60" /></p>
      <p>Password:<br /> <input type="password" name="password" size="20" maxlength="20" /></p><br /><br />
      <p><input type="submit" name="submit" value="Login"/></p>
    </form>

    <?

    if (isset($errors) && !empty($errors)) {
      echo '<h3>Error!</h3>
      <p class="error">The following error(s) occured:<br />';
      foreach ($errors as $msg) {
        echo " - $msg<br />\n";
      }
      echo 'Please try again.</p>';
    }

  ?>

  </div><!-- form container -->
<? include ('footer.php'); ?>
