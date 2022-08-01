<?php
  class SessionCookie {
    //Keep user logged if
    public static function keep_user_loggedin() {
      if (!isset($_SESSION['username'])) {
        if (!isset($_COOKIE['username'])) {
          header('location: signin.php');
          exit();
        } else {
          $_SESSION['username'] = $_COOKIE['username'];
          //This line seems to be redundent because session is checked in the beginning.
          if(!isset($_SESSION['username'])) {
            exit('Error: Session could not start');
          }
        }
      }
    }

    //Start a login session
    public static function start_loginsession(array $sessions) {
      $_SESSION['main_user_id'] = $sessions['user_id'];
      $_SESSION['username'] = $sessions['username'];
      $_SESSION['firstName'] = $sessions['firstName'];
      $_SESSION['lastName'] = $sessions['lastName'];
      $_SESSION['age'] = $sessions['age'];
      $_SESSION['gender'] = $sessions['gender'];
      $_SESSION['email'] = $sessions['email'];
      if (isset($sessions['profile_photo'])) {
        $_SESSION['profile_photo'] = $sessions['profile_photo'];
      }
      if (!isset($_SESSION['main_user_id']) &&
        !isset($_SESSION['username']) &&
        !isset($_SESSION['firstName']) &&
        !isset($_SESSION['lastName']) &&
        !isset($_SESSION['age']) &&
        !isset($_SESSION['region']) &&
        !isset($_SESSION['email']) &&
        !isset($_SESSION['profile_photo'])) {
        return false;
      } else {
        self::create_cookie($sessions['username']);
        return true;
      }
    }
    //Other user ID for messaging
    public static function other_user_session($other_user_id, $other_user_username) {
      $_SESSION['other_user_id'] = $other_user_id;
      $_SESSION['other_user_username'] = $other_user_username;
      if (!isset($_SESSION['other_user_id']) && !isset($_SESSION['other_user_username'])) {
        die('Session other user could not start');
      }
    }
    //Create cookie
    public static function create_cookie(string $username) {
      setcookie('username', $username, time() + 86400);
    }
    //Check if username cookie exists
    final public static function check_username_cookie() {
      if (isset($_COOKIE['username'])) {
        header('location: index.php');
      }
    }
    //Logout user
    public static function logout_user() {
      session_unset();
      session_destroy();
      setcookie('username', '', time() - 3600);
      header('location: signin.php');
    }
  }
 ?>
