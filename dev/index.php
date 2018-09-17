<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';

sec_session_start();

$title = TITLE;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

?>

	<body>
	
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>

			<div class="centre-container">

				<h2 <?php

					// Enable landing page animations
					if ($login_check['result'] === false) echo ('class="animated fadeIn"');

				?> >Just been for a run?</h2>
				<?php 

				$login_check = login_check($mysqli);

				if ($login_check['result'] === true) {
				echo '<h3><a href="' . HOMEURL . 'activity/">Activity</a></h3>';
				} else {
				echo '<h3 class="animated fadeIn"><a href="' . HOMEURL . 'register.php" class="modaal-ajax">Register</a> &sol; <a class="modaal-ajax" href="' . HOMEURL . 'login.php">Login</a></h3>';
				}
				?>

			</div>

			<main>

				<div class="container">

				<?php 

					$login_check = login_check($mysqli);

					if ($login_check['result'] === true) {

						echo '<p>Currently logged in as ' . htmlentities($_SESSION['name']) . '.</p>';

						echo "<p>Do you want to change user? <a href=" . HOMEURL . "/includes/logout.php'>Log out</a>.</p>";

					} else {
						echo "<p>Currently logged out.</p><a class='modaal-ajax' href='" . HOMEURL . "/login.php'>Login</a><p>If you don&apos;t have a login, please <a href='" . HOMEURL . "register.php' class='modaal-ajax'>register</a></p>";
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

		<?php include( $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php' ); ?>

	</body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/end-of-page.php'; ?>