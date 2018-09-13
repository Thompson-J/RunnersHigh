<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';

sec_session_start();

$title = "Record Activity" . TITLE_DELIMITOR . TITLE;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

?>

	<body>

		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>

		<main class="centre-container">

			<input id="activity" type="text" name="activity" placeholder="jog, bike ride, training&hellip;">

			<div id="timer">

				<p id="time_display">00:00:00</p>
				
				<button id="record_btn"></button>
				<button id="record_clear">Reset</button>

				<button id="record_submit">Submit</button>

			</div>

		</main>

		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>

	</body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/end-of-page.php'; ?>