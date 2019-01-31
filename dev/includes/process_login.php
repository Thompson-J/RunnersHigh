<?php
include_once 'functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.

header('Content-type:application/json;charset=utf-8');

$error_msg = '';

$data = (array) json_decode(file_get_contents('php://input'));

/*http_response_code(400);
var_dump($data);
die();*/

$login_check = login_check($mysqli);

if ($login_check['result'] === true) {

	$user_id = $_SESSION['user_id'];
	$name = $_SESSION['name'];

	$activity = array ('name' => $name, 'activity' => activity($user_id, $mysqli));

	//If the user has data
	if (count($activity) > 0) {

		// Send the user their activity data
		$response = array('result' => http_response_code(200), 'data' => $activity);
		echo (json_encode($response));
	
	} else {

		// Can't find any activity for the user
		$response = array('result' => http_response_code(200), 'data' => 'No recorded data.');
		echo (json_encode($response));
	}

}	else {

	if (!empty($data)) {

		// Sanitize and validate the data passed in
		$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL, FILTER_SANITIZE_STRING);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		  // Not a valid email
		  $error_msg .= 'The email address you entered is not valid. ';
		}
	 
		$password = filter_var($data['password'], FILTER_SANITIZE_STRING);
		// Check that the password (sha512 hashed) is 128 characters long
		// If it's not, the form data was malformed
		if (strlen($password) != 128) {

		  $error_msg .= 'Invalid password configuration. ';
		}

		if (empty($error_msg)) {
			
			// Attempt to login the user
			$login = login($email, $password, $mysqli);

			// Login success 
			if ($login['result'] === true) {

				$user_id = $login['user_id'];
				$name = $login['name'];

				$activity = array ('name' => $name, 'activity' => activity($user_id, $mysqli));

				//If the user has data
				if (count($activity) > 0) {

					http_response_code(200);

					$response = array('result' => http_response_code(), 'data' => $activity);
					echo (json_encode($response));

					//var_dump($session);

				}

			} else {

				switch ($login['response']) {

					case "bad-password":

						// Incorrect password
						$response = array('result' => 'bad-password');
						http_response_code(403);
						echo (json_encode($response));
						break;

					case "account-locked":

						// Account locked
						$response = array('result' => 'account-locked');
						http_response_code(429);
						echo (json_encode($response));
						break;

					case "user-nonexistant":

						// Email address not recognised
						$response = array('result' => 'user-nonexistant');
						http_response_code(403);
						echo (json_encode($response));
						break;

					}

				}
		} else {

			// The correct POST variables were not sent to this page. 
			$response = array('result' => $error_msg);
			http_response_code(400);
			echo (json_encode($response));

		}

	} else {

		$error_msg .= "Line " . __LINE__ . ': No input provided. ';

		$response = array('result' => $error_msg);
		http_response_code(400);
		echo (json_encode($response));

	}

}