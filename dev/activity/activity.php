<?php

// Split the name on whitespaces into an array
$firstname = explode( " ", $_SESSION['name'] );
// Get the first name of the user
$firstname = $firstname[0];

$title = $firstname . TITLE_DELIMITER . TITLE;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

?>

	<body>

		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>

			<main class="centre-container">
		
				<h1>
					Dashboard
				</h1>

				<p id="name"><?php echo $_SESSION['name']; ?></p>

				<table id="activity" class="horizontal-centre">

					<colgroup>
						<col>
						<col>
						<col>
						<col>
						<col>
						<col>
						<col>
						<col>
					</colgroup>

					<thead>
						<tr>

							<th>Session ID</th>
							<th>User Session</th>
							<th>Activity</th>
							<th>Date</th>
							<th>Distance</th>
							<th>Start Time</th>
							<th>Finish Time</th>
							<th>Duration</th>

						</tr>
					</thead>

					<tbody>
						

					</tbody>

					<tfoot>

						<td><i>new</i></td>
						<td></td>
						<td><input class="manual-input" id="manual_activity" type="text" name="activity" placeholder="jog, bike ride, training&hellip;"></td>
						<td><input class="manual-input" id="manual_date" type="date" name="date" placeholder="yyyy-mm-dd"></td>
						<td><input class="manual-input" id="manual_distance" type="text" name="distace"></td>
						<td><input class="manual-input" id="manual_starttime" type="time" name="starttime" step="5" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}" placeholder="HH:MM:SS"></td>
						<td><input class="manual-input" id="manual_finishtime" type="time" name="finishtime" step="5" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}" placeholder="HH:MM:SS"></td>
						<td><input class="manual-input" id="manual_duration" type="time" name="duration" step="5" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}" placeholder="HH:MM:SS"><button id="manual_submit">Submit</button></td>

					</tfoot>

				</table>

			</main>

		<?php include( $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php' ); ?>

	</body>

<?php

$addscripts = "<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.3/moment-with-locales.min.js'></script>
<link rel='stylesheet' type='text/css' href='" . HOMEURL . "tomtom/map.css'/>
<script src='" . HOMEURL . "'/tomtom/tomtom.min.js'></script>";

include $_SERVER['DOCUMENT_ROOT'] . '/includes/end-of-page.php'; ?>