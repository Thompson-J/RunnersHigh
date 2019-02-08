<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';

sec_session_start();

$title = TITLE;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

?>

			<div class="centre-container">

				<h1 <?php

					// Enable landing page animations
					if ($login_check['result'] === false) echo ('class="animated fadeIn"');

				?> >Just been for a run?</h1>
				<?php 

				$login_check = login_check($mysqli);

				if ($login_check['result'] === true) {
				echo '<h2><a href="' . HOMEURL . 'activity/">Activity</a></h2>';
				} else {
				echo '<h2 class="animated fadeIn"><a href="#register" data-modaal-type="inline" class="modaal">Register</a> &sol; <a data-modaal-type="inline" class="modaal" href="#login">Login</a></h2>';
				}
				?>

			</div>

			<main>

				<div class="container">

				<?php 

					$login_check = login_check($mysqli);

					if ($login_check['result'] === true) {

						echo '<p>Currently logged in as ' . htmlentities($_SESSION['name']) . '.</p>';

						echo "<p>Do you want to change user? <a href='" . HOMEURL . "/includes/logout.php'>Log out</a>.</p>";

					} else {
						echo "<p>Currently logged out.</p><a class='modaal' data-modaal-type='inline' href='#login'>Login</a><p>If you don&apos;t have a login, please <a href='#register' data-modaal-type='inline' class='modaal'>register</a></p>";
					}

				?>

					<section>
						
						<h3>Leaderboard</h3>

						<table id="leaderboard">
		
							<colgroup>

								<col class="col1">
								<col class="col2">
								<col class="col3">
							
							</colgroup>

							<thead>

								<tr>

									<th>Position</th>
									<th>Name</th>
									<th>Duration</th>

								</tr>

							</thead>

							<tbody>

							</tbody>

						</table>

					</section>

				</div>				

			</main>

<?php

$addscripts = "<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.3/moment-with-locales.min.js'></script>";

include $_SERVER['DOCUMENT_ROOT'] . '/includes/end_of_page.php'; ?>