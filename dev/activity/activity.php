<?php

// Split the name on whitespaces into an array
$firstname = explode( " ", $_SESSION['name'] );
// Get the first name of the user
$firstname = $firstname[0];

$title = $firstname . TITLE_DELIMITER . TITLE;

// Get the user's activity
$activity = activity($_SESSION['user_id'], $mysqli);

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

?>

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

							<th>Run ID</th>
							<th>Your session</th>
							<th>Activity</th>
							<th>Date</th>
							<th>Distance</th>
							<th>Start Time</th>
							<th>Finish Time</th>
							<th>Duration</th>

						</tr>
					</thead>

					<tbody>

						<?php

						if (!empty($activity)) {

							$i = 1;
							// Each run session
							foreach($activity as $row) {

								echo "<tr>";
								// Information about this run ?>
								<td><?php echo "<a href='analyse/?s=" . $row['run_id'] . "'>" . $row['run_id'] . "</a>" ?></td>
								<td><?php echo $i ?></td>
								<td><?php echo $row['activity'] ?></td>
								<td><?php $date = date("D jS M Y", strtotime($row['date']));
								$date = preg_replace("/(\d+)([a-z]+)/", "$1<sup>$2</sup>", $date);
								echo $date; ?></td>
								<td><?php $distance = preg_replace("/(\d)(\/)(\d)/", "<sup>$1</sup>&frasl;<sub>$3</sub>", $row['distance']);
								echo $distance; ?></td>
								<td><?php echo $row['start_time'] ?></td>
								<td><?php echo $row['finish_time'] ?></td>
								<td><?php echo $row['duration'] ?></td>

								<?php echo "</tr>";

								$i++;
							}

						}

						?>

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
			
<?php

$addscripts = '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.3/moment-with-locales.min.js"></script>
<link rel="stylesheet" type="text/css" href="' . HOMEURL . 'tomtom/map.css"/>
<script src="' . HOMEURL . '/tomtom/tomtom.min.js"></script>';

include $_SERVER['DOCUMENT_ROOT'] . '/includes/end-of-page.php'; ?>