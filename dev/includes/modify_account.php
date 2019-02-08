<?php

include_once 'functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.

$error_msg = '';

$email = (isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null);

$password = (isset($_POST['password']) ? filter_var($_POST['password'], FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null);
if (strlen($password) !== 128) {
  $error_msg .= 'Invalid password configuration. ';
}

$privacy = (isset($_POST['privacy']) ? filter_var($_POST['privacy'], FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null);

$delete_password = (isset($_POST['delete_password']) ? filter_var($_POST['delete_password'], FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null);
if (strlen($delete_password) !== 128) {
  $error_msg .= 'Invalid password confirmation. ';
}

$delete_cnfrm = (isset($_POST['delete_cnfrm']) ? filter_var($_POST['delete_cnfrm'], FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null);

$response = '';

var_dump($_POST, $email, $_SERVER['REQUEST_METHOD']);

//if(!empty($email))