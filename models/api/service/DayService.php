<?php
namespace api\service;
use api\Service;
use api\ServiceInterface;
use thedaysoflife\User;

class DayService extends Service implements ServiceInterface {
  protected $requiredPermission = ["day"];
  protected $user;

  /**
   * DayService constructor.
   * @param array $userData
   * @param array $para
   */
  public function __construct($userData, $para) {
    parent::__construct($userData, $para);
    $this->user = new User();
  }

  /**
   * Return the map of this service and actions
   * @return array
   */
  public static function map() {
    $map = ["service" => "service_day",
            "class"    => __CLASS__,
            "actions"  => ["get_day" => "getDay"],
    ];

    return $map;
  }

  /**
   * @return array|bool
   */
  public function getDay() {
    $this->requirePermission("day");
    $id = $this->hasPara("id");
    if ($id) {
      $day = $this->user->getDayById($id);

      return $day;
    }

    return false;
  }
}