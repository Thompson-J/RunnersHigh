<?php

// Split the name on whitespaces into an array
$firstname = explode( " ", $_SESSION['name'] );
// Get the first name of the user
$firstname = $firstname[0];

$title = 'Session ' . $_GET['s'] . TITLE_DELIMITER . TITLE;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

// Retrieve the session the user requested
// Start the session index at 1 instead of 0
$sessionid = $_GET['s'];

// Create variable for information about the run
$activity = $distance = $date = $start_time = $finish_time = $duration = '';

$activity_array = activityById($_SESSION['user_id'], $sessionid, $mysqli);

//var_dump($activity_array);
//die();

if($activity_array['result'] === false) {

	include $_SERVER['DOCUMENT_ROOT'] . '/error_pages/403.php';
	die();

} else {

	$sessionid = $activity_array['data']['session_id'];
	$activity = $activity_array['data']['activity'];
	$waypoints = json_encode($activity_array['data']['waypoints']);
	$distance = $activity_array['data']['distance'];
	$date = $activity_array['data']['date'];
	$start_time = $activity_array['data']['start_time'];
	$finish_time = $activity_array['data']['finish_time'];
	$duration = $activity_array['data']['duration'];

}

?>

	<body>

		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>

			<main class="centre-container">
		
				<h1>
					Analyse Session
				</h1>

				<div id='map'></div> 

				<p id="sessionid">Session ID: <?php echo $sessionid; ?></p>
				<p>Activity: <?php echo $activity; ?></p>
				<p>Geolocation: <span id="waypoints"><?php echo( $waypoints ); ?></span></p>
				<p>Distance: <?php echo $distance; ?></p>
				<p>Date: <?php echo $date; ?></p>
				<p>Start time: <?php echo $start_time; ?></p>
				<p>Finish time: <?php echo $finish_time; ?></p>
				<p>Duration: <?php echo $duration; ?></p>

				</main>

		<?php include( $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php' ); ?>

	</body>

<?php

$addscripts = "<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.3/moment-with-locales.min.js'></script>
<link rel='stylesheet' type='text/css' href='" . HOMEURL . "tomtom/map.css'/>
<script src='" . HOMEURL . "tomtom/tomtom.min.js'></script>
<script type='text/javascript' src='" . HOMEURL . "js/analyse.js'></script>";

include $_SERVER['DOCUMENT_ROOT'] . '/includes/end-of-page.php'; ?>