<?php
  namespace back;

  use core\Admin;
  use sys\System;
  use view\Back;

  class index extends Back {
    protected $title = "Dashboard Login";
    protected $contentTemplate = "index";
    protected $requiredPermission = false;

    public function __construct() {
      parent::__construct();

      if ($this->posted()) {
        if ($this->hasPost("email")) {
          $admin = new Admin();
          $inform = '';
          $email = $this->post["email"];
          $password = System::encryptPassword($this->post["password"]);
          $row = $admin->checkLogin($email, $password);

          if (isset($row['id'])) {
            $status = $row['status'];
            // if user is disable
            if ($status == ADMIN_DISABLE) {
              $inform = "This account has been disabled.";
            } // valid and active user
            else if ($status == ADMIN_ACTIVE) {
              System::setJWT($row["id"], $row["f_name"] . " " . $row["l_name"], $row['permission']);
              System::redirectTo("back/home/");
            }
          } else {
            //if invalid email and password
            $inform = "Incorrect username or password.";
          }
          $this->data = ["para" => $this->post, "inform" => $inform];
        }
      }
    }
  }