<?php
namespace back;
use view\Back;
use thedaysoflife\Admin;
use sys\System;

class index extends Back {
  protected $title = "Dashboard Login";
  protected $contentTemplate = "index";
  protected $requiredPermission = false;

  public function __construct() {
    parent::__construct();

    if ($this->posted()) {
      if ($this->hasPost("email")) {
        $admin    = new Admin();
        $email    = $this->post["email"];
        $password = System::encryptPassword($this->post["password"]);
        $row      = $admin->checkLogin($email, $password);
        $message  = "";
        if (isset($row['id'])) {
          $status = $row['status'];
          // if user is disable
          if ($status == ADMIN_DISABLE) {
            $message = $this->messages["DISABLE_USER"]["message"];
          } // valid and active user
          else if ($status == ADMIN_ACTIVE) {
            System::setJWT($row["id"], $row["f_name"] . " " . $row["l_name"], $row['permission']);
            $this->redirect("/back/home/");
          }
        }
        else {
          //if invalid email and password
          $message = $this->messages["INVALID_AUTHENTICATION"]["message"];
        }
        $this->para["message"] = $message;
      }
    }
  }
}