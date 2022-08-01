<?php
  declare(strict_types = 0);
  require 'autoloader/autoload.inc.php';
  session_start();
  SessionCookie::keep_user_loggedin();
  $edit = new Edit_profile($_SESSION['main_user_id']);
  $initDetails = [
    'firstName' => $edit->edit_previousDetails['firstName'],
    'lastName' => $edit->edit_previousDetails['lastName'],
    'age' => $edit->edit_previousDetails['age'],
    'gender' => $edit->edit_previousDetails['gender'],
    'email' => $edit->edit_previousDetails['email'],
    'username' => $edit->edit_previousDetails['username'],
    'password' => ''
  ];
  //Instentiate validation object
  $val = new Validate($initDetails);

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $gender = $_POST['gender'] ?? '';
    //Validate all the user inputs
    $val->val_name((string)$_POST['firstName'], 'firstName');
    $val->val_name((string)$_POST['lastName'], 'lastName');
    if ($val->username !== $_POST['username'])$val->val_username((string)$_POST['username']);
    if ( $val->email !== $_POST['email'])$val->val_email((string)$_POST['email']);
    $val->val_age((int)$_POST['age']);
    $val->val_gender((string)$gender);
    //Check if error holders are empty
    if (empty($val->firstNameErr) &&
        empty($val->lastNameErr) &&
        empty($val->usernameErr) &&
        empty($val->emailErr) &&
        empty($val->ageErr) &&
        empty($val->genderErr)) {
        //array with edit values
        $edited_details = ['firstName' => $val->firstName,
                          'lastName' => $val->lastName,
                          'username' => $val->username,
                          'email' => $val->email,
                          'age' => $val->age,
                          'gender' => $val->gender];
        //transferring values for edit
        if (!$edit->get_edited_velues((array)$edited_details, (int)$_SESSION['main_user_id'])) {
          die('couldn\'t edit');
        } else {
          $edited_details['user_id'] = $_SESSION['main_user_id'];
          if (!SessionCookie::start_loginsession((array)$edited_details)) {
            header('location: signin.php');
          }
        }
    }
  }


 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Edit Profile</title>
    <style>
       span.error{
         color: red;
       }
       img.profile-photo{
         height: 50px;
         width: auto;
       }
    </style>
  </head>
  <body>
    <h2>Edit Profile</h2>
    <div class="profile-picture">
      <img class="profile-photo" src="<?php echo $_SESSION['profile_photo']; ?>" alt="empty picture">
      <h4> <a href="change_pp.php">Change profile photo</a> </h4>
    </div>
    <form class="edit-profile" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <!--firstName-->
      <label>First Name</label><br>
      <span class='error'><?php echo $val->firstNameErr; ?></span>
      <input type="text" name="firstName" value="<?php echo $_POST['firstName'] ?? $val->firstName; ?>"><br>
      <!--lastName-->
      <label>Last Name</label><br>
      <span class='error'><?php echo $val->lastNameErr; ?></span>
      <input type="text" name="lastName" value="<?php echo $_POST['lastName'] ?? $val->lastName; ?>"><br>
      <!--username-->
      <label>Username</label><br>
      <span class='error'><?php echo $val->usernameErr ?></span>
      <input type="text" name="username" value="<?php echo $_POST['username'] ?? $val->username; ?>">





      <h2>Personal information</h2>
      <!--E-mail-->
      <label>Email</label><br>
      <span class='error'><?php echo $val->emailErr; ?></span>
      <input type="email" name="email" value="<?php echo $_POST['email'] ?? $val->email; ?>"><br>
      <!--Password-->
      <label>Age</label><br>
      <span class='error'><?php echo $val->ageErr; ?></span>
      <select class="age" name="age">
        <option value="">Select</option>
        <?php for ($age = 5; $age <= 100; $age++) {
          ?> <option value="<?php echo $age; ?>" <?php if ($age == $val->age) echo 'selected'; ?>><?php echo $age; ?></option> <?php
        } ?>
      </select><br>
      <!--Gender-->
      <label>Gender</label><br>
      <span class='error'><?php echo $val->genderErr; ?></span>
      <input type="radio" name="gender" value="male" <?php if (isset($val->gender) && $val->gender === 'male') echo 'checked'; ?>> <span>Male</span><br>
      <input type="radio" name="gender" value="female" <?php if (isset($val->gender) && $val->gender === 'female') echo 'checked'; ?>> <span>Female</span><br>
      <button type="submit" name="submit">Update</button>

    </form>
    <h4><a href="profile.php?username=<?php echo $_SESSION['username']; ?>">Back</a></h4>

  </body>
</html>
