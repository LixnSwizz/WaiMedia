<?php
  declare(strict_types = 0);
  require 'autoloader/autoload.inc.php';
  session_start();
  SessionCookie::keep_user_loggedin();
  $feed = new Newsfeed;
  $feed->get_newsfeed($_SESSION['main_user_id']);

 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>News Feed</title>
   </head>
   <body>
     <h2>NEWS FEED</h2>
     <h3><a href="index.php">Home</a></h3>
     <?php
      //Need to make use of  these fetched details
      /* post_id
          user_idfk
          text
          photo
          updated_at
          username*/
     while($results = $feed->newsfeed->fetch()) {
      echo '<tbody>';
       echo '<tr>';
       ?> <a href="profile.php?username=<?php echo $results['username']; ?>"><?php echo $results['username']; ?></a><br> <?php
        echo $results['text'].'<br>';
         echo $results['updated_at'].'<br>';
         echo '👍'.$feed->numberOfLikes($results['post_id']).' | ';
         echo "<a href='comments.php?pid=".$results['post_id']."'>🗨</a>".$feed->numberOfComments($results['post_id']).'<br>';
         echo '=========================<br>';
       echo '</tr>';
     }
      ?>

   </body>
 </html>
