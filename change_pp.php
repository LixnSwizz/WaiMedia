<?php
  declare(strict_types = 0);
  require 'autoloader/autoload.inc.php';
  session_start();
  SessionCookie::keep_user_loggedin();
  $change_pp = new Images_validation;

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    if (Images_validation::validate_images($_FILES, 'profile_photos') === true) {
      if ($change_pp->update_profile_photo(Images_validation::$final_profile_photo) === true) {
        header('location: edit_profile.php');
      }
    }

  }
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Upload profile photo</title>
  </head>
  <body>
    <label><?php echo Images_validation::$imageErr; ?></label>
    <form class="profile-photo" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
      <input type="file" name="profile-photo">
      <button type="submit" name="submit">Upload</button>
    </form>

    <!--Delete profile photo-->
    <div class="delete-pp">
      <?php
        if ($_SESSION['profile_photo'] !== 'empty_photo/empty_pp.webp') {
          ?>
            <h3><a href="delete_profile_photo.php">Delete profile photo</a></h3>
          <?php
        }
       ?>
    </div>
  </body>
</html>
