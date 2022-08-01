<?php
  /**
   * Test Input trait
   */
  trait TestInput {
    //Test input method
    final public function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
  }
 ?>
