<?php
  class Validate extends Queries {
    use TestInput;

    public $firstName;
    public $lastName;
    public $age;
    public $gender;
    public $region;
    public $email;
    public $username;
    public $password;

    public $firstNameErr;
    public $lastNameErr;
    public $ageErr;
    public $genderErr;
    public $regionErr;
    public $emailErr;
    public $usernameErr;
    public $passwordErr;

    public $signupErr;

    //Init properties
    public function __construct($initDetails) {
      $this->firstName = $initDetails['firstName'];
      $this->lastName = $initDetails['lastName'];
      $this->age = $initDetails['age'];
      $this->gender = $initDetails['gender'];
      $this->region = $initDetails['region'];
      $this->email = $initDetails['email'];
      $this->username = $initDetails['username'];
      $this->password = $initDetails['password'];

      $this->firstNameErr=$this->lastNameErr=$this->ageErr=$this->genderErr=$this->regionErr=$this->emailErr=$this->usernameErr=$this->passwordErr=$this->signupErr = '';
    }

    //Validate name
    final public function val_name(string $name, string $category) {
      if (empty($name)) {
        if ($category === 'firstName') {
          $this->firstNameErr = 'Field required<br>';
        } elseif ($category === 'lastName') {
          $this->lastNameErr = 'Field required<br>';
        } elseif ($category === 'region') {
          $this->regionErr = 'Field required<br>';
        } else {
          die ('Invalid Category');
        }
      } else {
        //Test input
        $name = $this->test_input($name);
        if (strlen($name) < 3) {
          $this->nameErr = 'Name too short!! Min 3 chars<br>';
        } elseif (strlen($name) > 100) {
          $this->nameErr = 'Name too long!! Max 100 chars<br>';
        } elseif (!filter_var($name, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-zA-Z\s]*$/']])) {
          $this->name = 'Only leter charecters and whire spaces are allowed<br>';
        } else {
          if ($category === 'firstName') {
            $this->firstName = $name;
          } elseif ($category === 'lastName') {
            $this->lastName = $name;
          } elseif ($category === 'region') {
            $this->region = $name;
          } else {
            die ('Invalid Category');
          }
        }
      }
    }
    //Validate age
    final public function val_age(int $age) {
      if (empty($age)) {
        $this->ageErr = 'Age field required<br>';
      } else {
        $age = $this->test_input($age);
        if (!$age >= 18 && !$age <= 100) {
          $this->ageErr = 'Invalid age input<br>';
        } else {
          $this->age = $age;
        }
      }
    }
    //Validate gender
    final public function val_gender(string $gender) {
      if (empty($gender)) {
        $this->genderErr = 'Gender field is required<br>';
      } else {
        $gender = $this->test_input($gender);
        if ($gender !== 'male' && $gender !== 'female') {
          $this->genderErr = 'Invalid gender input<br>';
        } else {
          $this->gender = $gender;
        }
      }
    }
    //Validate e-mail
    final public function val_email(string $email) {
      if (empty($email)) {
        $this->emailErr = 'Email field required<br>';
      } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $this->emailErr = 'Invalid email format<br>';
        } elseif ($this->check_existance('email', $email)) {
          $this->emailErr = 'Email '.$email.' already registered with another account<br>';
        } else {
          $this->email = $email;
        }
      }
    }

    //Validate username
    final public function val_username(string $username) {
      if (empty($username)) {
        $this->usernameErr = 'Username field required<br>';
      } else {
        //Test input
        $username = $this->test_input($username);
        if (strlen($username) < 3) {
          $this->usernameErr = 'Username too short!! Min 3 chars<br>';
        } elseif (strlen($username) > 30) {
          $this->usernameErr = 'Username too long!! Max 30 chars<br>';
        } elseif (!filter_var($username, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-z0-9_]*$/']])) {
          $this->usernameErr = 'Username only supports lowercase letters, numbers and underscores!! White space not allowed<br>';
        } elseif ($this->check_existance('username', $username)) {
          $this->usernameErr = 'Username '.$username.' has already been registered with another account<br>';
        } else {
          $this->username = $username;
        }
      }
    }

    //Validate password
    final public function val_password(string $password) {
      if (empty($password)) {
        $this->passwordErr = 'Password field required<br>';
      } else {
        //Test input
        $password = $this->test_input($password);
        if (strlen($password) < 6) {
          $this->passwordErr = 'Password too short!! Min 6 chars<br>';
        } elseif (strlen($password) > 200) {
          $this->passwordErr = 'Password too long!! Max 200 chars<br>';
        } elseif (filter_var($password, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/[\s]/']])) {
          $this->passwordErr = 'Password should not have white spaces<br>';
        } else {
          $this->password = $password;
        }
      }
    }

    //Check if error properties are empty and the others are initialised.
    final public function check_values():bool {

      if (
        empty($this->firstName) ||
        empty($this->lastName) ||
        empty($this->age) ||
        empty($this->gender) ||
        empty($this->region) ||
        empty($this->email) ||
        empty($this->username) ||
        empty($this->password) ||

        !empty($this->firstNameErr) ||
        !empty($this->lastNameErr) ||
        !empty($this->ageErr) ||
        !empty($this->genderErr) ||
        !empty($this->regionErr) ||
        !empty($this->emailErr) ||
        !empty($this->usernameErr) ||
        !empty($this->passwordErr)
      ) {
        return false;
      } else {
        return true;
      }
    }

    //Insert login details
    final public function insert_details($signup_details):bool {
      if (!$this->insert_signup_details($signup_details)) {
        return false;
      } else {
        return true;
      }
    }




  }
