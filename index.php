<?php
  declare(strict_types = 0);
  require 'autoloader/autoload.inc.php';
  session_start();
  SessionCookie::keep_user_loggedin();
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
     <h3>Home</h3>
     <h4><a href="profile.php?username=<?php echo $_SESSION['username']; ?>">Profile</a></h4>
     <h4><a href="newsfeed.php">News Feed</a></h4>
     <h4><a href='logout.php'>Sign out</a></h4>
     <h4><a href="messages.php">Messages</a></h4>
     <?php echo 'Username: '.$_SESSION['username'].'<br><br>'; echo $home->results_error; ?>
     <table>
         <?php
         while($user = $home->people->fetch()) {
          echo '<tbody>';
           echo '<tr>';
             echo "<td>".$user['firstName']." " .$user['lastName']."<br>";
             ?> <a href="profile.php?username=<?php echo $user['username']; ?>"><?php echo $user['username']; ?></a><br> <?php
             echo $user['age']."years" .$user['gender']."<br><a href='chat.php?id=".$user['user_id']."&username=".$user['username']."'>Chat</a>";
             echo '=========================';
           echo '</tr>';
         }
          ?>
     </table>
   </body>
 </html>
