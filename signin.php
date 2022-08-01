<?php
  declare(strict_types = 0);
  //error_reporting(0);
  session_start();
  require 'autoloader/autoload.inc.php';
  SessionCookie::check_username_cookie();
  //Instentiate LoginVal
  $logval = new LoginVal();
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $logval->logval_username($_POST['username']);
    $logval->logval_password($_POST['password']);
    if ($logval->check_login_values()) {
      if ($logval->login($logval->username, $logval->password) === true && empty($logval->loginErr)) {
        if (!SessionCookie::start_loginsession((array)$logval->sessionDetails)) {
          header('location: signin.php?error=Session_failed');
        } else {
          header('location: index.php');
        }
      }
    } else {
      echo 'Login Error<br>';
    }
  }

 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Sign Up</title>
     <style media="screen">
       span {
         color: red;
       }
     </style>
   </head>
   <body>
     <h2>Sign Up</h2>
     <span><?php echo $logval->loginErr; ?></span>
     <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
       <label>Username:</label><br>
       <input type="text" name="username" value="<?php echo $logval->username; ?>"><br>
       <label>Password:</label><br>
       <input type="password" name="password" value="<?php echo $logval->password; ?>"><br>
       <input type="submit" name="submit" value="Sign In">
       <span><a href='signup.php'> Sign Up</a></span>
     </form>
   </body>
 </html>
