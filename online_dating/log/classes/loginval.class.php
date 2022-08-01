<?php
  class LoginVal extends Queries {
    use TestInput;

    public $username;
    public $password;
    public $usernameErr;
    public $passwordErr;
    public $loginErr;

    public function __construct() {
      $this->username=$this->password=$this->usernameErr=$this->passwordErr=$this->loginErr='';
    }
    //Validate username
    final public function logval_username($username) {
      if (empty($username)) {
        $this->usernameErr = 'Username field required<br>';
      } else {
        $username = $this->test_input($username);
        $this->username = $username;
      }
    }
    //Validate password
    final public function logval_password($password) {
      if (empty($password)) {
        $this->password = 'Password field required<br>';
      } else {
        $password = $this->test_input($password);
        $this->password = $password;
      }
    }
    //Check loin values
    final public function check_login_values():bool {
      if (
        empty($this->username) ||
        empty($this->password) ||
        !empty($this->usernameErr) ||
        !empty($this->passwordErr)
      ) {
        return false;
      } else {
        return true;
      }
    }
    //Login user
    final public function login($username, $password) {
      if (!$result = $this->login_user($username)) {
        $this->loginErr = 'Incorect username or password<br>';
      } else {
        $row = $result->fetch();
        if (!password_verify($password, $row['password'])) {
          $this->loginErr = 'Incorect password<br>';
        } else {
          return true;
        }
      }
    }
  }
 ?>
