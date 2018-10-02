<?php

$title = "Record Activity" . TITLE_DELIMITER . TITLE;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

?>

	<body>

		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>

		<main class="centre-container">

			<input id="activity" type="text" name="activity" placeholder="jog, bike ride, training&hellip;">

			<div id="timer">

				<p id="time_display">00:00:00</p>
				<p id="distance"></p>
				
				<button id="record_btn"></button>
				<button id="record_clear">Reset</button>

				<button id="record_submit">Submit</button>

			</div>

			<div id='map'></div>

		</main>

		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>

	</body>

<?php

$addscripts = "<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.3/moment-with-locales.min.js'></script>
	<link rel='stylesheet' type='text/css' href='" . HOMEURL . "tomtom/map.css'/>
  <script src='" . HOMEURL . "tomtom/tomtom.min.js'></script>
	<script type='text/javascript' src='" . HOMEURL . "js/record-activity.js'></script>";

include $_SERVER['DOCUMENT_ROOT'] . '/includes/end-of-page.php'; ?>