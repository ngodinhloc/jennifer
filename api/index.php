<?php
  /**
   * Single entry point for API: all api point to this page with an action
   */
  require_once("../models/autoload.php");
  use sys\System;
  use api\API;

  $action = System::getGetPara("action");
  $api = new API();
  $api->$action();