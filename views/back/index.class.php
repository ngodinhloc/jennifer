<?php
namespace back;

use auth\Authentication;
use thedaysoflife\Admin;
use view\Back;

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
        $password = $this->authentication->encryptPassword($this->post["password"]);
        $row      = $admin->checkLogin($email, $password);
        $message  = "";
        if (isset($row['id'])) {
          $status = $row['status'];
          // if user is disable
          if ($status == Authentication::USER_STATUS_DISABLE) {
            $message = $this->authentication->messages["USER_STATUS_DISABLE"]["message"];
          } // valid and active user
          else if ($status == Authentication::USER_STATUS_ACTIVE) {
            $jwtData = ["id"         => $row["id"],
                        "name"       => $row["f_name"] . " " . $row["l_name"],
                        "permission" => $row['permission']];
            $this->authentication->setJWT($jwtData);
            $this->authentication->redirect("/back/home/");
          }
        }
        else {
          //if invalid email and password
          $message = $this->authentication->messages["INVALID_AUTHENTICATION"]["message"];
        }
        $this->data["message"] = $message;
      }
    }
  }
}