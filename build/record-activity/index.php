<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';

sec_session_start();

$login_check = login_check($mysqli);

if ($login_check['result'] === false) {

	include $_SERVER['DOCUMENT_ROOT'] . '/error_pages/403.php';
	die();

} else {

	include 'record-activity.php';

}