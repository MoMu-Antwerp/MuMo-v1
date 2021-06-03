<?php
session_start ();
if($_SERVER['HTTP_HOST'] != "localhost"){
  //Settings for the online server
  $con = mysqli_connect("localhost","database_user","password","database_name");
  $url = "https://example.com"; //base url ending on .be or .com
  $domain = "example.com"; //short url like it would be shown in an email adress behind the @
  $clustername = "Mumo"; 
  $logo = "assets\images\logo\Mumo final.png";
  $logo_w = "assets\images\logo\Mumo final_w.png"; //This can be changed to a different logo if desired
}else{
  //Settings of the localhost. Only needed whilst debuggin offline
  $con = mysqli_connect("localhost","root","root","mumo");
  $url = "http://localhost/MuMo-Dash"; //base url ending on .be or .com
  $domain = "localhost";
  $clustername = "Mumo";
  $logo = "assets\images\logo\Mumo final.png";
  $logo_w = "assets\images\logo\Mumo final_w.png";
}

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

$measurements_array = array(
  1 => array(
    "print_name" => "Temperature",
    "variable_name" => "temperature",
    "unit" => "°C",
    "type" => "float",
    "color" => "rgba(192, 57, 43, 0.75)",
    "calibration" => array(-5, 5),
    "range" => array(-50,75),
    "from_zero" => true
  ),
  2 => array(
    "print_name" => "Humidity",
    "variable_name" => "humidity",
    "unit" => "%rh",
    "type" => "float",
    "color" => "rgba(41, 128, 185, 0.75)",
    "calibration" => array(-10, 10),
    "range" => array(0,100),
    "from_zero" => true
  ),
  3 => array(
    "print_name" => "Illumination",
    "variable_name" => "illumination",
    "unit" => "Lux",
    "type" => "big_float",
    "color" => "rgba(241, 196, 15, 0.75)",
    "calibration" => array(0, 500),
    "range" => array(0,10000),
    "from_zero" => true
  ),
  4 => array(
    "print_name" => "Pressure",
    "variable_name" => "pressure",
    "unit" => "hPa",
    "type" => "float",
    "color" => "rgba(127, 140, 141, 0.75)",
    "calibration" => array(-50, 50),
    "range" => array(800,1200),
    "from_zero" => true
  ),
  5 => array(
    "print_name" => "VOC",
    "variable_name" => "voc",
    "unit" => "ppm",
    "type" => "int",
    "color" => "rgba(230, 126, 34, 0.75)",
    "calibration" => array(-100, 100),
    "range" => array(0,20000),
    "from_zero" => true
  ),
  6 => array(
    "print_name" => "Dust",
    "variable_name" => "dust",
    "unit" => "%",
    "type" => "float",
    "color" => "rgba(39, 174, 96, 0.75)",
    "calibration" => array(-10, 10),
    "range" => array(0,100),
    "from_zero" => true
  ),
  0 => array(
    "print_name" => "Battery",
    "variable_name" => "battery",
    "unit" => "%",
    "type" => "int",
    "color" => "rgba(192, 57, 43, 0.75)",
    "calibration" => array(0, 100),
    "range" => array(0,100),
    "from_zero" => true
  )
);


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
date_default_timezone_set("Europe/Brussels");
ini_set('precision', 6);
ini_set('serialize_precision', 6);

include "partials/general_functions.php";
include "partials/svg_icons.php";
