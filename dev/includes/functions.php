<?php

include_once 'psl-config.php';
include_once 'db_connect.php';

function sec_session_start() {
		$session_name = 'sec_session_id';   // Set a custom session name 
		$secure = SECURE;
		// This stops JavaScript being able to access the session id.
		$httponly = true;
		// Forces sessions to only use cookies.
		if (ini_set('session.use_only_cookies', 1) === FALSE) {
				// Could not initiate a safe session (ini_set)
				header("Location: ../error_pages/403.php");
				exit();
		}
		// Gets current cookies params.
		$cookieParams = session_get_cookie_params();
		session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
		// Sets the session name to the one set above.
		session_name($session_name);
		session_start();            // Start the PHP session 
		session_regenerate_id();    // regenerated the session, delete the old one. 
}

function login($email, $password, $mysqli) {
	// Using prepared statements means that SQL injection is not possible. 
	if ($stmt = $mysqli->prepare("SELECT user_id, name, email, password_hash 
		FROM users
		WHERE email = ?
		LIMIT 1")) {
		$stmt->bind_param('s', $email);  // Bind "$email" to parameter.
		$stmt->execute();    // Execute the prepared query.

		$stmt->store_result();
		if($stmt->num_rows !== 1) {
			
			//exit('More or less than 1 row');
			// No user exists.
			return array('result' => false, 'response' => "user-nonexistant");

		}

		// set variables from result.
		$stmt->bind_result($user_id, $name, $db_email, $db_password);
		$stmt->fetch();
		$stmt->close();

		// If the user exists we check if the account is locked
		// from too many login attempts 

		//echo ("Email exists.");

		if (checkbrute($user_id, $mysqli) === false) {

			// Check if the password in the database matches
			// the password the user submitted. We are using
			// the password_verify function to avoid timing attacks.
			if (password_verify($password, $db_password)) {

				// Password is correct!
				// Get the user-agent string of the user.
				$user_browser = $_SERVER['HTTP_USER_AGENT'];
				// XSS protection as we might print this value
				$user_id = preg_replace("/[^0-9]+/", "", $user_id);
				$_SESSION['user_id'] = $user_id;
				// XSS protection as we might print this value
				$db_email = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $db_email);
				$_SESSION['email'] = $db_email;
				$_SESSION['name'] = str_replace('_', ' ', $name);
				$_SESSION['login_string'] = hash('sha512', $db_password . $user_browser);
				
				// Login successful.
				return array('result' => true, 'response' => 'login-successful', 'user_id' => $user_id, 'name' => $name);

			} else {
				// Password is not correct
				// We record this attempt in the database
				$now = time();

				$stmt = $mysqli->prepare("INSERT INTO login_attempts (user_id, `time`) VALUES (?, ?)");
				$stmt->bind_param("ii", $user_id, $now);
  			$stmt->execute();
  			$stmt->close();

				return array('result' => false, 'response' => 'bad-password', 'user_id' => null);

			}
			
			
		} else {

			// Send an email to user saying their account is locked…
			// 

			// Account is locked 
			//echo "account is locked";
			return array('result' => false, 'response' => 'account-locked', 'user_id' => null);

		}
	}

}

function login_check($mysqli) {
	
	// Check if all session variables are set 
	if ( isset($_SESSION['user_id'], $_SESSION['email'], $_SESSION['login_string'] ) ) {

		$user_id = $_SESSION['user_id'];
		$login_string = $_SESSION['login_string'];
		$email = $_SESSION['email'];

		// Get the user-agent string of the user.
		$user_browser = $_SERVER['HTTP_USER_AGENT'];

		if ($stmt = $mysqli->prepare("SELECT password_hash 
																	FROM users 
																	WHERE user_id = ? LIMIT 1")) {
			// Bind "$user_id" to parameter. 
			$stmt->bind_param('i', $user_id);
			$stmt->execute();   // Execute the prepared query.
			$stmt->store_result();

			if ($stmt->num_rows == 1) {
				// If the user exists get variables from result.
				$stmt->bind_result($password);
				$stmt->fetch();
				$login_check = hash('sha512', $password . $user_browser);

				if (hash_equals($login_check, $login_string)){
					// Logged In!!!! 
					return array('result' => true, 'user_id' => $user_id);
				} else {
					// Not logged in 
					return array('result' => false);
				}
			} else {
				// Not logged in 
				return array('result' => false);
			}
		} else {
			// Not logged in 
			return array('result' => false);
		}
	} else {
		// Not logged in 
		return array('result' => false);
	}
}

function checkbrute($user_id, $mysqli) {
	// Get timestamp of current time 
	$now = time();

	// All login attempts are counted from the past 2 hours. 
	$valid_attempts = $now - (2 * 60 * 60);

	if ($stmt = $mysqli->prepare("SELECT `time` 
																FROM login_attempts 
																WHERE user_id = ? 
																AND `time` > ?")) {
		$stmt->bind_param('ii', $user_id, $valid_attempts);

		// Execute the prepared query. 
		$stmt->execute();
		$stmt->store_result();

		// If there have been more than 5 failed logins 
		if ($stmt->num_rows > 5) {
				return true;
		} else {
				return false;
		}
	}
}

function esc_url($url) {
 
		if ('' == $url) {
				return $url;
		}
 
		$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
		$strip = array('%0d', '%0a', '%0D', '%0A');
		$url = (string) $url;
 
		$count = 1;
		while ($count) {
				$url = str_replace($strip, '', $url, $count);
		}
 
		$url = str_replace(';//', '://', $url);
 
		$url = htmlentities($url);
 
		$url = str_replace('&amp;', '&#038;', $url);
		$url = str_replace("'", '&#039;', $url);
 
		if ($url[0] !== '/') {
				// We're only interested in relative links from $_SERVER['PHP_SELF']
				return '';
		} else {
				return $url;
		}
}

function activity($user_id, $mysqli) {

	if ($stmt = $mysqli->prepare("SELECT activity, distance, `date`, start_time, finish_time, duration
															FROM user_sessions
															WHERE user_id = ?
															")) {

		$stmt->bind_param('i', $user_id);  // Bind "$user_id" to parameter.
		$stmt->execute();    // Execute the prepared query.

		$result = $stmt->get_result();
		if($result->num_rows === 0) return;
		
		while($row = $result->fetch_assoc()) {
		
		  $arr[] = $row;
		
		}

		return $arr;

	}

}

function leaderboard($mysqli) {

// Get timestamp of current time and the time an hour ago
$now = time();
$onehourago = $now - (60 * 60);

	// ** Disable the leaderboard cache

	// If the leaderboard cache needs to be updated
	/* if (apcu_fetch('leaderboard_update') < $onehourago) {

		apcu_store('leaderboard_update', $now);

		*/

		// Retrieve the 5 quickest runs from the table user_sessions
		$stmt = $mysqli->prepare("SELECT name, duration
															FROM user_sessions
															INNER JOIN users ON users.user_id = user_sessions.user_id
															ORDER BY duration
															LIMIT 5");

		$stmt->execute();

		$result = $stmt->get_result();
		
		if($result->num_rows === 0) exit('No rows');
		
		while($row = $result->fetch_assoc()) {
		
		  $leaderboard[] = $row;
		
		}

		/*

		// Store the leaderboard data in the APCu cache store for 1 hour
		if (apcu_store('leaderboard', $leaderboard, 3600)) {

			*/

			// Successfully wrote the leaderboard data to the cache store so return the data
			return array('result' => true, 'data' => $leaderboard);

			/*

		} else {

			// APCU-store failed
			return array('result' => false);

		}

	// The leaderboard is up-to-date
	} else {

		$leaderboard = apcu_fetch('leaderboard');

		return array('result' => true, 'data' => $leaderboard);

	}

	*/

}

// Enter activity into the database
function manual_entry($userid, $data, $mysqli) {

	// Write SQL to insert data into the database
	$insert_stmt = $mysqli -> prepare("INSERT INTO user_sessions (user_id, activity, distance, `date`, start_time, finish_time, duration) VALUES (?, ?, ?, ?, ?, ?, ?)");

	// Bind activity data to the SQL statement
	$insert_stmt -> bind_param('issssss', $userid, $data['activity'], $data['distance'], $data['date'], $data['start_time'], $data['finish_time'], $data['duration']);

	// Insert the activity into the database
	if ( $insert_stmt->execute() ) {
		
		// The insert was successful
		$insert_stmt->close();

		return true;

	} else {
		
		return false;
		
	}

}