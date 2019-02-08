<?php

include_once 'functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.

if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/img/user_photos')) {
	mkdir($_SERVER['DOCUMENT_ROOT'] . '/img/user_photos', 0777, true);
}

// Receieve the JSON from the client
if(!empty($_FILES['photo'])) {
	$photo = $_FILES['photo'];
	echo(move_uploaded_file($photo['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/img/user_photos/' . $_SESSION['user_id'] . '.jpg'));
	$response .= 'Uploaded photo. ';
}

var_dump($_FILES);