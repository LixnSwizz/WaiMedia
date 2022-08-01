<?php
  declare(strict_types = 0);
  require 'autoloader/autoload.inc.php';
  SessionCookie::check_username_cookie();
  //Initialise properties
  $initDetails = [
    'firstName' => '',
    'lastName' => '',
    'age' => '',
    'gender' => '',
    'region' => '',
    'email' => '',
    'username' => '',
    'password' => ''
  ];
  //Instentiate validation object
  $val = new Validate($initDetails);

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    //Check if post gender is not set and init empty
    $gender = $_POST['gender'] ?? '';
    //Validate all the user inputs
    $val->val_name((string)$_POST['firstName'], 'firstName');
    $val->val_name((string)$_POST['lastName'], 'lastName');
    $val->val_age((int)$_POST['age']);
    $val->val_gender((string)$gender);
    $val->val_name((string)$_POST['region'], 'region');
    $val->val_email((string)$_POST['email']);
    $val->val_username((string)$_POST['username']);
    $val->val_password((string)$_POST['password']);
    //Check sign up details
    if ($val->check_values()) {
      $hash_pass = password_hash($val->password, PASSWORD_DEFAULT);
      //Array with sign up details
      $signup_details = [
        'firstName' => $val->firstName,
        'lastName' => $val->lastName,
        'age' => $val->age,
        'gender' => $val->gender,
        'region' => $val->region,
        'email' => $val->email,
        'username' => $val->username,
        'password' => $hash_pass
      ];
      if ($val->insert_details($signup_details)) {
        SessionCookie::start_loginsession($val->username);
      } else {
        echo 'Sign up failed';
      }
    }

  }
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Sign Up</title>
     <style>
        span.error{
          color: red;
        }
     </style>
   </head>
   <body>
     <h2>Sign Up</h2>
     <span class='error'><?php echo $val->signupErr; ?></span>
     <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
       <!--Name-->
       <label>First Name</label><br>
       <span class='error'><?php echo $val->firstNameErr; ?></span>
       <input type="text" name="firstName" value="<?php echo $val->firstName; ?>"><br>
       <!--Surname-->
       <label>Last Name</label><br>
       <span class='error'><?php echo $val->lastNameErr; ?></span>
       <input type="text" name="lastName" value="<?php echo $val->lastName; ?>"><br>
       <!--Age-->
       <label>Age</label><br>
       <span class='error'><?php echo $val->ageErr; ?></span>
       <select class="age" name="age">
         <option value="">Select</option>
         <?php for ($age = 18; $age <= 100; $age++) {
           echo '<option value='.$age.'>'.$age.'</option>';
         } ?>
       </select><br>
       <!--Gender-->
       <label>Gender</label><br>
       <span class='error'><?php echo $val->genderErr; ?></span>
       <input type="radio" name="gender" value="male" <?php if (isset($val->gender) && $val->gender === 'male') echo 'checked'; ?>> <span>Male</span><br>
       <input type="radio" name="gender" value="female" <?php if (isset($val->gender) && $val->gender === 'female') echo 'checked'; ?>> <span>Female</span><br>
       <!--Region-->
       <label>Region</label><br>
       <span class='error'><?php echo $val->regionErr; ?></span>
       <select class="region" name="region">
         <option value="">Select</option>
         <option value="manzini">Manzini</option>
         <option value="mbabane">Mbabane</option>
         <option value="Lubombo">Lubombo</option>
         <option value="Nshiselweni">Nshiselweni</option>
       </select><br>
       <!--E-mail-->
       <label>Email</label><br>
       <span class='error'><?php echo $val->emailErr; ?></span>
       <input type="email" name="email" value="<?php echo $val->email; ?>"><br>
       <!--Username-->
       <label>Username</label><br>
       <span class='error'><?php echo $val->usernameErr; ?></span>
       <input type="text" name="username" value="<?php echo $val->username; ?>"><br>
       <!--Password-->
       <label>Password</label><br>
       <span class='error'><?php echo $val->passwordErr; ?></span>
       <input type="password" name="password" value="<?php echo $val->password; ?>"><br>
       <!--Submit-->
       <input type="submit" name="submit" value="Sign Up">
       <span><a href='signin.php'> Back</a></span>
     </form>
   </body>
 </html>
