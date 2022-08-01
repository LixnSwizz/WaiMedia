<?php
  declare(strict_types = 0);
  //error_reporting(0);
  require 'autoloader/autoload.inc.php';
  session_start();
  SessionCookie::keep_user_loggedin();
  $pid = $_GET['pid'] ?? '';
  // $username = $_GET['username'] ?? '';
  // if (!empty($id) && !empty($username)) {
  //   SessionCookie::other_user_session($id, $username);
  // }
  $post = new Newsfeed;
  $post->postForCommentSection($pid);
  ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Comments</title>
    <style media="screen">
      .profile_photo{
        height: 30px;
        width: auto;
        border-radius:  50%;
      }
    </style>
  </head>
  <body>
    <h1>Comments</h1>
    <?php
    /**
     * //Make use of
     * post_id
     * user_idfk
     * text
     * photo
     * updated_at
     * username
     * profile_photo
     */
      while($results = $post->postForCommentSection->fetch()) {
        ?><img class="profile_photo" src="<?php echo $results['profile_photo']; ?>" alt="profile photo"><?php
        echo "<a href='profile.php?username=".$results['username']."'>".$results['username']."</a>";
        echo '<p>'.$results['text'].'</p>';
        echo '👍'.$post->numberOfLikes((int)$results['post_id']).' | ';
        echo "<a href='#'>🗨</a>".$post->numberOfComments((int)$results['post_id']).'<br>';
        //initialising post id because the array losses its value outside the while loop and it should be a constant
        $post_idfk = $results['post_id'];
      }
     ?>
     <h4>===========================================================</h4>
     <!--Comments section below-->
     <?php
        // pc.comment_id,
        //  pc.post_idfk,
        //  pc.user_idfk,
        //  pc.comment,
        //  pc.updated_at,
        //  u.username,
        //  u.profile_photo

        $post->comments($post_idfk);
        if (!empty($post->comments)) {
          while ($results = $post->comments->fetch()) {
            echo '<br>';
            ?><img class="profile_photo" src="<?php echo $results['profile_photo']; ?>"><?php
            echo "<a href='profile.php?username=".$results['username']."'>".$results['username']."</a><br>";
            echo $results['comment'].'<br>';
            echo '👍'.$post->numberOfCommentLikes((int)$results['comment_id'])->numberOfCommentLikes['comment_likes'].' | ';
            echo '🔁'.$post->totalReplies($results['comment_id'])->totalReplies['total_replies'];
          }
        }
      ?>
     <h3>Still have to work on the sending problem that removes the post id from the url</h3>
     <!--<form class="comment" action="<?php //echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
       <textarea name="comment" rows="2" cols="18" placeholder="Type commment"></textarea>
       <button type="submit" name="submit">SEND</button>
     </form>-->
  </body>
</html>
