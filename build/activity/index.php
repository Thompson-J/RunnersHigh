<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';

sec_session_start();

$login_check = login_check($mysqli);

if ($login_check['result'] === false) {

	header('Location: ../error_pages/403.php');
	die();

} else {

	include 'activity.php';

}