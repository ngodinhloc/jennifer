<?php
  /**
   * Single entry point for controllers: all ajax actions point to this page with a pair of {action, controller}
   */
  require_once("../models/autoload.php");
  use jennifer\sys\System;

  $system = new System();
  $system->loadController()->runController();
