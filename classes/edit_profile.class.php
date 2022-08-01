<?php
  class Edit_profile extends DBQueries {
    public $edit_previousDetails;

    //Getting the edit details
    public function __construct(int $main_user_id) {
      $results = $this->fetch_edit_details($main_user_id);
      $this->edit_previousDetails = $results->fetch();
    }
    //Edited values
    final public function get_edited_velues(array $edited_details, int $main_user_id): bool {
      if (!$this->update_profile($edited_details, $main_user_id)) {
        return false;
      } else {
        return true;
      }
    }
  }
