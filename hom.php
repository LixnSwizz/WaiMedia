<?php
  declare(strict_types = 0);
  session_start();
  require 'autoloader/autoload.inc.php';
  include 'functions.php';
  SessionCookie::check_username_cookie();
  $home = new Home();
  $home->display_people($_SESSION['username']);
  $user = $home->people;

 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Home</title>
   </head>
   <body>
     <div class="header">

     </div>
     <h2><a href="profile.php">Profile</a></h2>
   </body>
 </html>
