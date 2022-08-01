<?php
  class Follow extends DBQueries {
    public $num_followers;
    public $num_following;
    public $followers;
    public $following;
    public $empty = '';
    public $user_details;

    //Get number of main user followers
    public function count_followers($username) {
      $results = $this->fetch_count_followers($username);
      $row = $results->fetch();
      $this->num_followers = $row['followers'];
      $results = null;

    }

    //Get number of pople main user following
    public function count_following($username) {
      $results = $this->fetch_count_following($username);
      $row = $results->fetch();
      $this->num_following = $row['following'];
      $results = null;
    }

    //Followers
    final public function get_mass_followers($main_user_id) {
      $results = $this->fetch_followers($main_user_id);
      if (!$results->rowCount() > 0) {
        $this->empty = '0 Followers';
      } else {
        $this->followers = $results;
      }
      $results = null;
    }

    //Following
    final public function get_mass_following($main_user_id) {
      $results = $this->fetch_following($main_user_id);
      if (!$results->rowCount() > 0) {
        $this->empty = '0 Following';
      } else {
        $this->following = $results;
      }
      $results = null;
    }
    //Getting user details
    public function get_user_details($username) {
      $results = $this->fetch_user_details($username);
      if (!$results->rowCount() > 0) {
        die('There is an error getting user details');
      } else {
        $this->user_details = $results->fetch();
      }
    }
    //Following ids
    public function get_following_ids($main_user_id) {
      $results = $this->fetch_following_ids($main_user_id);
      return $results;
    }
    //Follow people
    public function follow_people($main_user_id, $following_id) {
      if (!$this->insert_follow_people($main_user_id, $following_id)) {
        die('There was an error in following');
      } else {
        return true;
      }
      #Follow function
      //$follow->follow_people($_SESSION['main_user_id'], 3);
    }
    //Unfollow
    public function unfollow_people($main_user_id, $following_id) {
      if (!$this->delete_follow_people($main_user_id, $following_id)) {
        die('There was an error in unfollowing');
      } else {
        return true;
      }
      #Unfollow people
      //$follow->unfollow_people($_SESSION['main_user_id'], 5);
    }





  }
