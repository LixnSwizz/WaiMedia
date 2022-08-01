<?php
//require 'config.class.php';
  //Should consider making the class abtract
  /*abstract*/ class Dbnewsfeed extends Config {
    //Fetch newsfeed from database
    protected function fetch_newsfeed(int $main_user_id): object {
      $sql = "SELECT p.post_id, p.user_idfk, p.text, p.photo, p.updated_at, u.username
  	     FROM posts p
         INNER JOIN user_followers uf
         INNER JOIN users u
         ON uf.user_idfk=p.user_idfk
         WHERE uf.follower_idfk = $main_user_id AND u.user_id = p.user_idfk
         ORDER BY p.updated_at ASC LIMIT 10";
         $stmt = $this->connect()->prepare($sql);
         $stmt->execute([$main_user_id]);
         return $stmt;
    }
    //Insert likes
    protected function insert_likes(int $user_idfk, int $post_idfk):bool {
         $sql = "INSERT INTO post_likes (post_idfk, user_idfk) VALUES ($post_idfk, $user_idfk)";
         if (!$this->connect()->query($sql)) {
           die('Error when inserting like to a post');
         } else {
           return true;
         }
    }
    //Cout post likes
    protected function fetch_numberOfLikes(int $post_idfk):object {
      $sql = "SELECT COUNT(pl.post_idfk) AS likes
          	     FROM post_likes pl
                 INNER JOIN posts p
                 ON pl.post_idfk=p.post_id
                 WHERE pl.post_idfk = $post_idfk";
      return $this->connect()->query($sql);
    }
    //Count number of post comments
    protected function fetch_numberOfComments(int $post_idfk): object {
      $sql = "SELECT COUNT(pc.post_idfk) AS comments
          	     FROM comments pc
                 INNER JOIN posts p
                 ON pc.post_idfk=p.post_id
                 WHERE pc.post_idfk = $post_idfk";
      return $this->connect()->query($sql);
    }
    //Fetch a comment_idting section
    protected function fetch_postForCommentSection(int $post_id) {
      $sql = "SELECT p.post_id, p.user_idfk, p.text, p.photo, p.updated_at, u.username, u.profile_photo
	               FROM posts p
                 INNER JOIN users u ON p.user_idfk=u.user_id
                 WHERE post_id=$post_id";
      return $this->connect()->query($sql);
    }
    //Fetch posts by a specific user to show in the profile page
    protected function fetch_postForProfile(string $username): object {
      $sql = "SELECT p.post_id, p.user_idfk, p.text, p.photo, p.updated_at
          	     FROM posts p
                 INNER JOIN users u ON p.user_idfk=u.user_id
                 WHERE u.username=? ORDER BY p.updated_at LIMIT 10";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$username]);
      return $stmt;
    }
    //Inserting a comment
    protected function insertCommentToDb(int $post_idfk, int $user_idfk, string $comment): bool {
      $sql = "INSERT INTO comments (post_idfk, user_idfk, comment) VALUES (?,?,?)";
      $stmt = $this->connect()->prepare($sql);
      if (!$stmt->execute([$post_idfk, $user_idfk, $comment])) {
        return false;
      } else {
        return true;
      }
    }
    //fetch comments
    protected function fetchComments(int $post_idfk): object {
      $sql = "SELECT pc.comment_id, pc.post_idfk, pc.user_idfk, pc.comment, pc.updated_at, u.username, u.profile_photo
	              FROM comments pc
                JOIN posts p ON p.post_id=pc.post_idfk
                JOIN users u ON u.user_id=pc.user_idfk
                WHERE pc.post_idfk=$post_idfk ORDER BY pc.updated_at ASC LIMIT 10";
      return $this->connect()->query($sql);
    }
    //inserting comments likes
    protected function insertCommmentLikeDB(int $comment_idfk, int $user_idfk): bool {
      $sql = "SELECT comment_like_id FROM comment_likes WHERE comment_idfk = $comment_idfk AND user_idfk = $user_idfk";
      if (!$results = $this->connect()->query($sql)) {
        die('Could not select comment_likeDB {insertCommmentLikeDB}');
      } else {
        if ($results->rowCount() > 0) {
          die('You already liked this post');
        } else {
          $sql = "INSERT INTO comment_likes (comment_idfk, user_idfk) VALUES ($comment_idfk, $user_idfk)";
          if (!$this->connect()->query($sql)) {
            return false;
          } else {
            return true;
          }
        }
      }
    }
    //Fetch number of comment likes
    protected function fetchNumberOfCommentLikes(int $comment_idfk): object {
      $sql = "SELECT COUNT(user_idfk) AS comment_likes FROM comment_likes WHERE comment_idfk = $comment_idfk;";
      return $this->connect()->query($sql);
    }
    //Insert reply into mysql db
    protected function insertReplyDB(int $comment_idfk, int $user_idfk, string $reply): bool {
      $sql = "INSERT INTO comment_replys (comment_idfk, user_idfk, reply) VALUES (?,?,?);";
      $stmt = $this->connect()->prepare($sql);
      if (!$stmt->execute([$comment_idfk, $user_idfk, $reply])) {
        return false;
      } else {
        return true;
      }
    }
    //Fetch replys
    protected function fetchReplyToComment(int $comment_idfk): object {
      $sql = "SELECT cr.comment_replys_id, cr.comment_idfk, cr.user_idfk, cr.reply, u.username
          	     FROM comment_replys cr
                 INNER JOIN users u ON u.user_id=cr.user_idfk
                 WHERE cr.comment_idfk = $comment_idfk ORDER BY cr.updated_at ASC LIMIT 10;";
      return $this->connect()->query($sql);
    }
    //Fetch total replies
    protected function fetchTotalReplies(int $comment_idfk) {
      $sql = "SELECT COUNT(cr.user_idfk) AS total_replies
          	     FROM comment_replys cr
                 INNER JOIN comments c ON c.comment_id=cr.comment_idfk
                 WHERE c.comment_id = $comment_idfk;";
      return $this->connect()->query($sql);
    }

  }

  // $test = new Dbnewsfeed;
  // $result = $test->fetch_postForCommentSection(1);
  // foreach ($result as $key => $value) {
  //   echo $key.'=>'.$value.'<br>';
  // }
