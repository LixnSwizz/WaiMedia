<?php
  declare(strict_types = 0);
  require 'autoloader/autoload.inc.php';
  session_start();
  SessionCookie::keep_user_loggedin();
  $delete = new Images_validation;

  if ($delete->delete_profile_photo()) {
    header('location:edit_profile.php');
  }
