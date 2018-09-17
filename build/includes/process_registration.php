<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('content-type: application/json');

$error_msg = null;

// Include functions one time
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';

// receive the JSON Post data
$data = (array) json_decode( file_get_contents( 'php://input' ), true );

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

// Email validity and password validity have been checked client side.
// This should should be adequate as nobody gains any advantage from
// breaking these rules.
//

// check existing email
$prep_stmt = "SELECT user_id FROM users WHERE email = ? LIMIT 1";
$stmt = $mysqli->prepare($prep_stmt);

if ($stmt) {
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows == 1) {
    // A user with this email already exists
    $error_msg .= 'A user with this email already exists. ';
    $stmt->close();
  }
} else {
  $error_msg .= 'Database error line ' . __LINE__ . '. ';
  $stmt->close();
}

if (empty($error_msg)) {

    // Create hashed password using the password_hash function.
    // This function salts it with a random salt and can be verified with
    // the password_verify function.
    $password = password_hash($password, PASSWORD_BCRYPT);

    $fullname = filter_var($data['fullname'], FILTER_SANITIZE_STRING);

    // Insert the new user into the database 
    $prep_stmt = "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($prep_stmt);

    if($stmt) {

        $stmt->bind_param('sss', $fullname, $email, $password);
        // Execute the prepared query.
        if ($stmt->execute()) {
            
        } else {
            $error_msg .= "Database insert error. ";
        }
    } else {
        $error_msg .= "Couldn't prepare insert. ";
    }

    if (empty($error_msg)) {
      
      // The user was registered in the database
      $response = array('result' => 'success');
      echo ( json_encode($response) );

    } else {

      // The user wasn't registered in the database
      $response = array('result' => $error_msg);
      http_response_code(403);
      echo ( json_encode($response) );

    }
    
} else {

    // The user wasn't registered in the database
    $response = array('result' => $error_msg);
    http_response_code(403);
    echo ( json_encode($response) );    

}

?>