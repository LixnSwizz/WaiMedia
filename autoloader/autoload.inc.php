<?php
  spl_autoload_register('autoload');

  function autoload($className) {
    $path = 'classes/';
    $ext = '.class.php';
    $fullPath = $path.$className.$ext;
    //Check existance
    if (!file_exists($fullPath)) {
      return false;
    } else {
      require $fullPath;
    }
  }
 ?>
