<?php
  abstract class Queries extends Config {

    //Check if details are unique.
    final protected function check_existance($column, $details) {
      $sql = "SELECT $column FROM users_tb WHERE $column=? LIMIT 1;";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$details]);
      if ($stmt->rowCount() > 0) {
        return true;
      } else {
        return false;
      }
    }

    //Insert sign up details into DB
    final protected function insert_signup_details($signup_details) {
      $sql = 'INSERT INTO users_tb (firstName, lastName, age, gender, region, email, username, password) VALUES (?,?,?,?,?,?,?,?);';
      $stmt = $this->connect()->prepare($sql);
      if (!$stmt->execute([
        $signup_details['firstName'],
        $signup_details['lastName'],
        $signup_details['age'],
        $signup_details['gender'],
        $signup_details['region'],
        $signup_details['email'],
        $signup_details['username'],
        $signup_details['password']
        ])) {
        return false;
      } else {
        return true;
      }
    }

    //Login user
    final protected function login_user($username) {
      $sql = 'SELECT password FROM users_tb WHERE username=?;';
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$username]);
      if (!$stmt->rowCount() > 0) {
        return false;
      } else {
        return $stmt;
      }
    }

  }
 ?>
