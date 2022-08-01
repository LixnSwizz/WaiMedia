<?php
declare(strict_types = 0);
require 'autoloader/autoload.inc.php';
session_start();
SessionCookie::keep_user_loggedin();
  $follow = new Follow;
  $id = $_GET['id'];
  $follow->get_mass_following($id);
  $following = $follow->get_following_ids($_SESSION['main_user_id']);
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Following</title>
  </head>
  <body>
    <label><?php echo $follow->empty; ?></label>
    <?php
        if (isset($follow->following)) {
          while($row = $following->fetch()) {
            $ids[] = $row['user_idfk'];
          }

          while($details = $follow->following->fetch()) {
            echo "<a href='profile.php?username=".$details['username']."'>".$details['username']."</a>";
            echo (in_array($details['user_id'], $ids)) ? 'Following' : 'Follow';
            echo '<br>';
          }
        }
        $follow->following = null;
     ?>
  </body>
</html>
