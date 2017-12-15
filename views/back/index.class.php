<?php
namespace back;

use jennifer\auth\Authentication;
use jennifer\view\ViewInterface;
use thedaysoflife\model\Admin;
use thedaysoflife\view\ViewBack;

class index extends ViewBack implements ViewInterface {
  protected $title = "Dashboard Login";
  protected $contentTemplate = "index";
  protected $requiredPermission = false;

  public function __construct() {
    parent::__construct();
    $this->admin = new Admin();
  }

  public function prepare() {
    if ($this->posted()) {
      if ($this->hasPost("email")) {
        $email    = $this->post["email"];
        $password = $this->authentication->encryptPassword($this->post["password"]);
        $row      = $this->admin->checkLogin($email, $password);
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