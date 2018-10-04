<nav class="sidenav">

	<button class="burger-button active" type="button">

		<div class="bar1"></div>
		<div class="bar2"></div>
		<div class="bar3"></div>

	</button>

	<ul>

	<?php 

		$login_check = login_check($mysqli);

		if ($login_check['result'] === true) {

			echo '<li><a href="' . HOMEURL . 'activity/">Activity</a></li>
			<li><a href="' . HOMEURL . 'record-activity/">Record Activity</a></li>
			<li><a href="' . HOMEURL . 'includes/logout.php">Logout</a></li>';
		
		} else {
		
			echo '<li><a href="' . HOMEURL . 'register.php" class="modaal-ajax">Register</a></li>
			<li><a class="modaal-ajax" href="'. HOMEURL . 'login.php">Login</a></li>';
		
		}
	?>
	</ul>

</nav>

<!-- Main content -->
<div id="site-wrapper" <?php
		
	// Enable landing page animations
	if ($login_check['result'] === false) echo ('class="animated fadeIn"');

?>>

	<header class="navbar">

		<a href="<?php echo HOMEURL ?>" id="logo">
			<img src="<?php echo HOMEURL; ?>img/logo.svg" alt="Runners&apos; High">
		</a>

		<nav>

			<ul>

				<li><a href="<?php echo HOMEURL ?>">Home</a></li>

				<?php 

					$login_check = login_check($mysqli);

					if ($login_check['result'] === true) {

						echo '<li><a href="' . HOMEURL . 'activity/">Activity</a></li>
						<li><a href="' . HOMEURL . 'record-activity/">Record Activity</a></li>
						<li><a href="' . HOMEURL . 'includes/logout.php">Logout</a></li>';
					
					} else {
					
						echo '<li><a href="' . HOMEURL . 'register.php" class="modaal-ajax">Register</a></li>
						<li><a class="modaal-ajax" href="' . HOMEURL . 'login.php">Login</a></li>';
					
					}
				?>

			</ul>
		
		</nav>

		<button class="burger-button" type="button">

			<div class="bar1"></div>
			<div class="bar2"></div>
			<div class="bar3"></div>

		</button>

	</header>