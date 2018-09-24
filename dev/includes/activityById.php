<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-type:application/json;charset=utf-8');

include_once 'functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.


// Retrieve the session the user requested
$data = (array) json_decode(file_get_contents('php://input'));
// Sanitize the data passed in
$sessionid = filter_var($data['s'], FILTER_SANITIZE_STRING);

$activity_array = activityById($_SESSION['user_id'], $sessionid, $mysqli);

if($activity_array['result'] === false) {

	// Activity doesn't exist or user doesn't have permission to view it
	$response = array('result' => $data['s']);
	http_response_code(403);
	echo (json_encode($response));

} else {

	http_response_code(200);

	$response = array('result' => http_response_code(), 'data' => $activity_array['data']);
	echo (json_encode($response));

}