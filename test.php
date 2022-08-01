<?php
  declare(strict_types = 0);
  require 'autoloader/autoload.inc.php';
  session_start();
  SessionCookie::keep_user_loggedin();
  $follow = new Follow;

  $follow->unfollow_people($_SESSION['main_user_id'], 5);
