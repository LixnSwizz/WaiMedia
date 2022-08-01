<?php
  spl_autoload_register('autoload');

  function autoload($className) {
    $path = 'classes/';
    $ext = '.class.php';
    $fullPath = $path.$className.$ext;

    $configPath = '../config/';
    $configExt = '.class.php';
    $configFullPath = $configPath.$className.$configExt;

    if (!file_exists($fullPath)) {
      if (!file_exists($configFullPath)) {
        return false;
      } else {
        require $configFullPath;
      }
    } else {
      require $fullPath;
    }
  }
 ?>
