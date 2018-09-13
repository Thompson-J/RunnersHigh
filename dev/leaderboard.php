<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

header('content-type: application/json');

$leaderboard = leaderboard($mysqli);

if ($leaderboard['result'] === true) {

	//http_response_code(200);
	$response = array('result' => http_response_code(200), 'data' => $leaderboard['data']);
	
	echo (json_encode($response));

} else {

	http_response_code(500);

}