<? # script 12.2 - login_functions.inc.php
  function redirect_user($page = 'index.php') {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $url = rtrim($url, '/\\');
    $url .= '/' . $page;

    // redirect the user
    header("Location: $url");
    exit(); // Quit the script.

  } // end of redirect_user()

  function check_login($dbc, $username = '', $password = '') {
    $errors = array();
    if (empty($username)) {
      $errors[] = 'You forgot to enter your username.';
    } else {
      $e = mysqli_real_escape_string ($dbc, trim($username));
    }

    if (empty($password)) {
      $errors[] = 'You forgot to enter your password.';
    } else {
      $p = mysqli_real_escape_string($dbc, trim($password));
    }

    if (empty($errors)) {
      $q = "SELECT user_id, first_name FROM users WHERE username='$e' AND password=SHA1('$p')";
      $r = mysqli_query($dbc, $q);
      if (mysqli_num_rows($r) == 1) {
        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
        return array(true, $row);
      } else { // not a match
        $errors[] = 'The username address and password entered do not match those on file.';
      }
    } // end of empty($errors) if

    return array(false, $errors);

  } // end of check_login()

  function create_multi($r) {
    $multidimensional_array = array();
    $row_array = array();
    while($row = mysqli_fetch_array($r, MYSQLI_NUM)) {
      $multidimensional_array[] = $row;
    } // end of while loop
    return $multidimensional_array;
  } // end of create_multi($r);
