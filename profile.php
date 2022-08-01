<?php
  declare(strict_types = 0);
  require 'autoloader/autoload.inc.php';
  session_start();
  SessionCookie::keep_user_loggedin();
  $follow = new Follow;
  $username = $_GET['username'];
  $follow->get_user_details($username);
  $follow->count_followers((int)$follow->user_details['user_id']);
  $follow->count_following((int)$follow->user_details['user_id']);
  $posts = new Newsfeed;
  $posts->postForProfile($username);

  ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Profile</title>
    <style>
       img.profile-photo{
         height: 50px;
         width: auto;
       }
    </style>
  </head>
  <body>
    <h3><a href="index.php">Home</a></h3>
    <?php
      echo '<h2>'.$username.'</h2>';
      ?>
        <div class="profile-photo">
          <img class="profile-photo" src="<?php echo $follow->user_details['profile_photo']; ?>" alt="empty picture">
        </div>
      <?php
      if ($username == $_SESSION['username']) {
        ?> <h3><a href="edit_profile.php">Edit Profile</a></h3> <?php
      }
      echo '<h4>'.$follow->user_details['firstName'].' '.$follow->user_details['lastName'].'</h4>';
      echo $follow->num_followers."<a href='followers.php?id=".$follow->user_details['user_id']."'> Followers</a><br>";
      echo $follow->num_following."<a href='following.php?id=".$follow->user_details['user_id']."'> Following</a><br>";

      echo '----------------------------------------<br>';
     ?>
     <h3>POSTS</h3>

     <!--Posts Section-->
     <?php
     /*
      *post_id
      *user_idfk
      *text
      *photo
      *updated_at
      */
        if (!empty($posts->feedForProfile)) {
          while ($results = $posts->feedForProfile->fetch()) {
            echo '<h4>'.$username.'</h4>';
            echo $results['text'].'<br>';
            echo '👍'.$posts->numberOfLikes($results['post_id']).' | ';
            echo "<a href='comments.php?pid=".$results['post_id']."'>🗨</a>".$posts->numberOfComments($results['post_id']).'<br>';
            echo '------------------------------<br>';
          }
        } else {
          //Will display if a user does not have any post made
          echo 'No news feed available';
        }

      ?>
  </body>
</html>
