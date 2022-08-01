<?php
 //require 'Dbnewsfeed.class.php';
// require 'testinput.class.php';
  class Newsfeed extends Dbnewsfeed {
    use TestInput;
    public $no_newsfeed = '';
    public $newsfeed;
    public $postRemoved = '';
    public $postForCommentSection;
    public $feedForProfile = '';
    public $comments;
    public $numberOfCommentLikes;
    public $totalReplies;

    //Get news feed object
    public function get_newsfeed(int $main_user_id) {
      $results = $this->fetch_newsfeed($main_user_id);
      if (!$results->rowCount() > 0) {
        $this->no_newsfeed = 'Sorry...You have no news feed!!';
      } else {
        $this->newsfeed = $results;
        $results = null;
      }
    }
    //Get post number of likes
    public function numberOfLikes(int $post_idfk) {
      $result = $this->fetch_numberOfLikes($post_idfk)->fetch();
      return $result['likes'];
    }
    //Get number of comments
    public function numberOfComments(int $post_idfk) {
      $result = $this->fetch_numberOfComments($post_idfk)->fetch();
      return $result['comments'];
    }
    //Post for postForCommentingSection
    public function postForCommentSection(int $post_id) {
      $result = $this->fetch_postForCommentSection($post_id);
      if (!$result->rowCount() > 0) {
        $this->postRemoved = 'Post has been removed';
      } else {
        $this->postForCommentSection = $result;
      }
    }
    //New feed to dispay on user's profile
    public function postForProfile(string $username) {
      $result = $this->fetch_postForProfile($username);
      if ($result->rowCount() > 0) {
        $this->feedForProfile = $result;
      }
    }
    //Inserting comment
    //The below code works fine, its just a metter of implementing it on ther system
    /*public function insertComment(int $post_idfk, int $user_idfk, string $comment): bool {
      if (!$this->insertCommentToDb($post_idfk, $user_idfk, $this->test_input($comment))) {
        return false;
      } else {
        return true;
      }
    }*/

    //Get Comments
    public function comments(int $post_idfk) {
      $results = $this->fetchComments($post_idfk);
      if (!$results->rowCount() > 0) {
        $this->comments = '';
      } else {
        $this->comments = $results;
      }
    }
    //Insert comment likes
    //Need to implement this
  /*
    public function insertCommmentLike(int $comment_idfk, int $user_idfk) {
      if (!$this->insertCommmentLikeDB($comment_idfk, $user_idfk)) {
        return false;
      } else {
         return true;
      }
    }
    */

    //Get number of comment likes
    public function numberOfCommentLikes(int $comment_idfk) {
      $this->numberOfCommentLikes = $this->fetchNumberOfCommentLikes($comment_idfk)->fetch();
      return $this;
    }
    //Insert reply to comment
    /*
    public function insertReply(int $comment_idfk, int $user_idfk, string $reply) {
      if(!$this->insertReplyDB($comment_idfk, $user_idfk, $this->test_input($reply))) {
        return false;
      } else {
        return true;
      }
    }*/
    //Count replies to a comment
    public function totalReplies(int $comment_idfk) {
      $this->totalReplies = $this->fetchTotalReplies($comment_idfk)->fetch();
      return $this;
    }

    //Select reply on comment
    public function getReplyToComment(int $comment_idfk) {
       return $results = $this->fetchReplyToComment($comment_idfk)->fetch();
    }
    //unsetting all properties
    public function __destruct() {
      unset($this->no_newsfeed);
      unset($this->newsfeed);
      unset($this->postRemoved);
      unset($this->postForCommentSection);
      unset($this->feedForProfile);
      unset($this->comments);
      unset($this->numberOfCommentLikes);
      unset($this->totalReplies);
    }


  }
  // $test = new Newsfeed;
  // $results = $test->numberOfCommentLikes(5);
  // var_dump($results);
  // var_dump($results);
  // var_dump($test->numberOfCommentLikes);
  // var_dump($results);
  //  $results = $results->fetch();
  //  var_dump($results);
  // var_dump($result);
