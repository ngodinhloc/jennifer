<?php
/**
 * Single entry point for API: all api point to this page with an request
 */
require_once("../models/autoload.php");
use api\API;
use sys\Globals;

/*
 * Sample api request
 * $hash is given to users when they registered for api service
 *
$data    = ["userID" => 1000, "permission" => ["user", "day"]];
$hash     = JWT::encode($data, Authentication::JWT_KEY_API);
$request = ["hash"    => $hash,
            "service" => "service_day",
            "action"  => "get_day",
            "para"    => ["id" => "100151", "json" => false,]];
$url     = "www.thedaysoflife.com/api/?req=" . json_encode($request);
$url     = 'www.thedaysoflife.com/api/?req={"hash":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySUQiOjEwMDAsInBlcm1pc3Npb24iOlsidXNlciIsImRheSJdfQ.f2ieaIQd8OrTK7UrA4BqDwhgg1NpzLV7OdOGIWBbQNU","service":"service_day","action":"get_day","para":{"id":"100151","json":false}}';
*/
$req = Globals::get("req");
$api = new API();
$api->process($req)->run();