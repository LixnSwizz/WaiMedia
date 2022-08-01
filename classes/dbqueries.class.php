<?php
  abstract class DBQueries extends Config {
    protected $returns;
    //Fetch user details
    final protected function fetch_user_details($username) {
      $sql = "SELECT user_id, firstName, lastName, username, email, 	profile_photo FROM users WHERE username = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$username]);
      return $stmt;
    }

    //Check if details are unique.
    final protected function check_existance($column, $details) {
      $sql = "SELECT $column FROM users WHERE $column=? LIMIT 1;";
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
      $sql = 'INSERT INTO users (firstName, lastName, age, gender, email, profile_photo, username, password) VALUES (?,?,?,?,?,?,?,?)';
      $stmt = $this->connect()->prepare($sql);
      if (!$stmt->execute([
        $signup_details['firstName'],
        $signup_details['lastName'],
        $signup_details['age'],
        $signup_details['gender'],
        $signup_details['email'],
        $signup_details['profile_photo'],
        $signup_details['username'],
        $signup_details['password']
        ])) {
        return false;
      } else {
        $username = $signup_details['username'];
        $sql = "SELECT user_id, profile_photo FROM users WHERE username = '$username';";
        if (!$stmt = $this->connect()->query($sql)) {
          die('Could not select user_id');
        } else {
          $this->returns = $stmt;
        }
        return true;
      }
    }

    //Login user
    final protected function login_user($username) {
      $sql = 'SELECT * FROM users WHERE username=?';
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$username]);
      if (!$stmt->rowCount() > 0) {
        return false;
      } else {
        return $stmt;
      }
    }

    //Fetch people
    final protected function fetch_people(string $username): object {
      $sql = "SELECT * FROM users WHERE username != '$username' LIMIT 13";

      if (!$stmt = $this->connect()->query($sql)) {
        die('Error, results couldn\'t fetch');
      } else {
        return $stmt;
      }
    }

    //Inserting message
    final protected function insert_message($input_msg, int $main_user_id, int $other_user_id, $ip_address) {
      $sql = "INSERT INTO conversation (text_msg, user_idfk_from, user_idfk_to, ip) VALUES (?,?,?,?)";

      $stmt = $this->connect()->prepare($sql);
      if (!$stmt->execute([$input_msg, $main_user_id, $other_user_id, $ip_address])) {
        return false;
      } else {
        return true;
      }
    }

    //Fetch message
    final protected function fetch_message_db(int $other_user): object {
      $main_user = $_SESSION['main_user_id'];
      $sql = "SELECT text_msg, user_idfk_from, user_idfk_to, time FROM conversation WHERE user_idfk_from = '$main_user' AND user_idfk_to = '$other_user' OR user_idfk_from = '$other_user' AND user_idfk_to = '$main_user' ORDER BY time ASC LIMIT 50";

      if (!$stmt = $this->connect()->query($sql)) {
        die('Error, results couldn\'t fetch');
      } else {
        return $stmt;
      }
    }

    //fetch edit details
    final protected function fetch_edit_details(int $main_user_id): object {
      $sql = "SELECT firstName, lastName, username, gender, age, email FROM users WHERE user_id = ?";

      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$main_user_id]);
      if (!$stmt->rowCount() > 0) {
        die('Error, edit results could not fetch');
      } else {
        return $stmt;
      }
    }
    //Update user profile
    final protected function update_profile(array $edited_details, $main_user_id): bool {
      $firstName = $edited_details['firstName'];
      $lastName = $edited_details['lastName'];
      $username = $edited_details['username'];
      $email = $edited_details['email'];
      $age = $edited_details['age'];
      $gender = $edited_details['gender'];

      $sql = "UPDATE users SET firstName = ?, lastName = ?, username = ?, email = ?, age = ?, gender = ? WHERE user_id=?";
      $stmt = $this->connect()->prepare($sql);
      if (!$stmt->execute([$firstName, $lastName, $username, $email, $age, $gender, $main_user_id])) {
        return false;
      } else {
        return true;
      }
    }
    //Select followers
    final protected function fetch_count_followers($user_id): object {
      $sql = "SELECT COUNT(user_idfk) AS followers FROM user_followers WHERE user_idfk = '$user_id'";

      if (!$stmt = $this->connect()->query($sql)) {
        die('Could not get followers');
      } else {
        return $stmt;
      }
    }
    //Select following
    final protected function fetch_count_following($user_id): object {
      $sql = "SELECT COUNT(follower_idfk) AS following FROM user_followers WHERE follower_idfk = '$user_id'";
      if (!$stmt = $this->connect()->query($sql)) {
        die('Could not get followers');
      } else {
        return $stmt;
      }
    }
    //Fetch followers
    final protected function fetch_followers($user_id): object {
      $sql = 'SELECT users.user_id, users.username
              FROM users
              INNER JOIN user_followers
              ON
              users.user_id=user_followers.follower_idfk
              WHERE user_followers.user_idfk=?';
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$user_id]);
      return $stmt;
    }
    //Fetch following
    final protected function fetch_following($user_id): object {
      $sql = 'SELECT users.user_id, users.username
              FROM users
              INNER JOIN user_followers
              ON
              users.user_id=user_followers.user_idfk
      WHERE user_followers.follower_idfk=?';
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$user_id]);
      return $stmt;
    }
    //Inserting profile photo
    final protected function updating_profile_photo($final_profile_photo, $main_user_id): bool {
      $sql = 'UPDATE users SET profile_photo=? WHERE user_id=?' ;
      $stmt = $this->connect()->prepare($sql);
      if (!$stmt->execute([$final_profile_photo, $main_user_id])) {
        return false;
      } else {
        return true;
      }
    }

    //Deleting profile photo
    final protected function deleting_profile_photo($empty_pp, $main_user_id): bool {
      $sql = 'UPDATE users SET profile_photo=? WHERE user_id=?';
      $stmt = $this->connect()->prepare($sql);
      if ($stmt->execute([$empty_pp, $main_user_id])) {
        return true;
      }
    }
    //Fetch following ids
    final protected function fetch_following_ids($main_user_id): object {
      $sql = 'SELECT user_idfk FROM user_followers WHERE follower_idfk=?';
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$main_user_id]);
      return $stmt;
    }
    //Follow people
    final protected function insert_follow_people($main_user_id, $following_id) {
      $sql = 'INSERT INTO user_followers (user_idfk, follower_idfk) VALUES (?,?)';
      $stmt = $this->connect()->prepare($sql);
      if (!$stmt->execute([$following_id, $main_user_id])) {
        return false;
      } else {
        return true;
      }
    }
    //Unfollow people
    final public function delete_follow_people($main_user_id, $following_id) {
      $sql = 'DELETE FROM user_followers WHERE user_idfk=? AND follower_idfk=?';
      $stmt = $this->connect()->prepare($sql);
      if (!$stmt->execute([$following_id, $main_user_id])) {
        return false;
      } else {
        return true;
      }
    }






  }
 ?>
