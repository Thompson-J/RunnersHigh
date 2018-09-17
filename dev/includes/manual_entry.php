<?php
include_once 'functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.

header('content-type: application/json');

// Receieve the JSON from the client
$data = file_get_contents( 'php://input' );

// Convert it from a string into an array
$data = json_decode( $data, true );

if ( manual_entry($_SESSION['user_id'], $data, $mysqli) === true ) {
	
	// Success
	$response = array( 'result' => http_response_code(200) );
	echo (json_encode($response));

} else {

	// Error
	$response = array( 'result' => http_response_code(400) );
	echo (json_encode($response));

}