<?php
  class Home extends DBQueries {
    use TestInput;

    public $results_error;
    public $people;
    public $empty_message;
    public $chat;

    public function __construct() {
      $this->people=$this->results_error=$this->empty_message=$this->chat='';
    }
    //Getting people to display
    final public function display_people($username) {
      $results = $this->fetch_people($username);
      if (!$results->rowCount() > 0) {
        $this->results_error = 'There are no people available';
      } else {
        $this->people = $results;
      }
    }
    //Getting messages
    final public function get_chat_messages($other_user) {
      $results = $this->fetch_message_db($other_user);
      if (!$results->rowCount() > 0) {
        $this->empty_message = 'There are no messages available for this chat';
      } else {
        $this->chat = $results;
      }
    }
    //Input text message
    final public function message_validation($input_msg, $other_user_id) {
      if (!empty($input_msg)) {
        $input_msg = $this->test_input($input_msg);
        if (strlen($input_msg) > 500) {
          echo 'Have exceeded charecter limit<br>';
        } else {
          $ip_address = $_SERVER['REMOTE_ADDR'];
          $main_user_id = $_SESSION['main_user_id'];

          if (!$this->insert_message($input_msg, (int)$main_user_id, (int)$other_user_id, $ip_address)) {
            return false;
          } else {
            return true;
          }
        }
      }
    }
  }
