<?php
  class SessionCookie {
    //Keep user logged if
    public static function keep_user_loggedin() {
      session_start();
      if (!isset($_SESSION['username'])) {
        if (!isset($_COOKIE['username'])) {
          header('location: ../log/signin.php');
          exit();
        } else {
          $_SESSION['username'] = $_COOKIE['username'];
          if(!isset($_SESSION['username'])) {
            exit('Error: Session cound not start');
          }
        }
      }
    }
    //Start a login session
    public static function start_loginsession(string $username) {
      session_start();
      $_SESSION['username'] = $username;
      if (!isset($_SESSION['username'])) {
        header('location: signin.php?error=Session_failed');
      } else {
        self::create_cookie($username);
        header('location: ../home/index.php');
      }
    }
    //Create cookie
    public static function create_cookie(string $username) {
      setcookie('username', $username, time() + 86400);
    }
    //Check if username cookie exists
    final public static function check_username_cookie() {
      if (isset($_COOKIE['username'])) {
        header('location: ../home/index.php');
      }
    }
    //Logout user
    public static function logout_user() {
      session_start();
      session_unset();
      session_destroy();
      setcookie('username', '', time() - 3600);
      header('location: ../log/signin.php');
    }
  }
 ?>
