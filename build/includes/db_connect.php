<?php

include_once 'psl-config.php';
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE, PORT);
if($mysqli->connect_error) {
  exit('Error connecting to database'); //Should be a message a typical user could understand in production
}
$mysqli->set_charset("utf8");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);