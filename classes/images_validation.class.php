<?php
  class Images_validation extends DBQueries{
    use TestInput;

    public static $imageErr = '';
    public static $final_profile_photo = '';

    public static function validate_images($image, $folder) {
      if (empty($image['profile-photo']['tmp_name'])) {
        self::$imageErr = 'Please select an image';
      } else {
        $fileExt = explode('.', $image['profile-photo']['name']);
        $fileExt = strtolower(end($fileExt));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($fileExt, $allowed)) {
          self::$imageErr = 'You cannot upload this file type';
        } elseif ($image['profile-photo']['error'] !== 0) {
          self::$imageErr = 'There was an error uploading your photo';
        } elseif ($image['profile-photo']['size'] > 10000000) {
          self::$imageErr = 'Image size is too big';
        } else {
          $fileName = uniqid('', true).'.'.$fileExt;
          $profile_photo = $folder.'/'.$fileName;
          if (!move_uploaded_file($image['profile-photo']['tmp_name'], $profile_photo)) {
            self::$imageErr = 'Image uploading was unsuccessfull';
          } else {
            self::$final_profile_photo = $profile_photo;
            return true;
          }
        }
      }
    }
    public function update_profile_photo($final_profile_photo) {
      $final_profile_photo = $this->test_input($final_profile_photo);
      if (!$this->updating_profile_photo($final_profile_photo, $_SESSION['main_user_id'])) {
        die('Image link could not be updated');
      } else {
        if ($_SESSION['profile_photo'] !== 'empty_photo/empty_pp.webp') {
          if (!file_exists($_SESSION['profile_photo'])) {
            die('Such file do not exist');
          } else {
            unlink($_SESSION['profile_photo']);
            $_SESSION['profile_photo'] = $final_profile_photo;
            return true;
          }
        } else {
          $_SESSION['profile_photo'] = $final_profile_photo;
          return true;
        }
      }
    }

    //Deleting profile photo
    public function delete_profile_photo(): bool {
      unlink($_SESSION['profile_photo']);
      if ($this->deleting_profile_photo('empty_photo/empty_pp.webp', $_SESSION['main_user_id'])) {
        $_SESSION['profile_photo'] = 'empty_photo/empty_pp.webp';
        return true;
      }
    }










  }
