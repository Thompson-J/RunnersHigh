<!doctype html>
<html lang="en">
	<head>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Having an exercise routine is a factor in personal well-being, so I produced Runners’ High as a tool to aid enthusiast athletes during and after their runs. The project is the work of myself (josh-thompson.com), with design and computer development guidance from the tutors in the School of Art, Design and Architecture at University of Huddersfield.">
		<meta name="author" content="Josh Thompson">
		<meta name="keywords" content="josh, thompson, graphic, design, designer, student, front end, back end, web development, PHP, Javascript, jQuery, CSS, HTML, Adobe, CC, Muse, Illustrator, Photoshop, InDesign, runners high, endorphins, #hudGDA, University, of, Huddersfield, ADA, RFID, Processing, product, technology, digital, jogging, exercise"/>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<link rel="icon" href="favicon.png">

		<title><?php echo $title; ?></title>
		
		<link href="<?php echo HOMEURL; ?>css/styles.css" rel="stylesheet" type="text/css">

	</head>

	<body>

		<div id="login" style="display: none;">

			<h1>Login</h1>

			<form id="login_form">

				<div class="input-rule" id="login_bad_email"></div>
				<input type="email" id="login_email" name="login_email" autofocus="autofocus" placeholder="Email…" required>
				
				<div class="input-rule" id="login_bad_password"></div>
				<input type="password" id="login_password" name="login_password" placeholder="Password…" required>
				
				<label class="remember-user">
					<input type="checkbox" id="login_remember_user" name="login_remember_user">
					<span>Remember user?</span>
				</label>
				
				<button type="button" onclick="login()">Login</button>

				<p id="login_reset_link"><a href="#" onclick="passwordResetModal()">Reset your password&hellip;</a></p>

			</form>

		</div>

		<div id="register" style="display: none;">
			
			<h1>Register</h1>

			<form id="register_form">
				
				<div class="input-rule" id="register_bad_name"></div>
				<input id="register_fullname" type="text" name="register_fullname" placeholder="Name…" required>

				<label for="register_gender">Male</label>
				<input id="register_gender_male" type="radio" name="register_gender" value="male">
				<label for="register_gender">Female</label>
				<input id="register_gender_female" type="radio" name="register_gender" value="female">

				<input type="date" id="register_dob" name="register_dob" placeholder="yyyy-mm-dd">

				<div class="input-rule" id="register_bad_email"></div>
				<input id="register_email" type="email" name="register_email" placeholder="Email…" required>

				<div class="input-rule" id="register_bad_password"></div>
				<input id="register_password" type="password" name="register_password" placeholder="Password…" required>

				<div class="input-rule" id="register_bad_password_cnfrm"></div>
				<input id="register_password_cnfrm" type="password" name="register_password_cnfrm" placeholder="Confirm password…" required>

				<label for="register_privacy">Public</label>
				<input type="radio" id="register_privacy_public" name="register_privacy" value="public">
				<label for="register_privacy">Private</label>
				<input type="radio" id="register_privacy_private" name="register_privacy" value="private">

				<!-- Added type="button" to change default button behaviour -->
				<button type="button" id="register_submit" name="register_submit" onclick="registerSubmit()">Submit</button>

			</form>

		</div>

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
					<li><a href="' . HOMEURL . 'account/">Account</a></li>
					<li><a href="' . HOMEURL . 'includes/logout.php">Logout</a></li>';
				
				} else {
				
					echo '<li><a href="#register" data-modaal-type="inline" class="modaal">Register</a></li>
					<li><a data-modaal-type="inline" class="modaal" href="#login">Login</a></li>';
				
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
								<li><a href="' . HOMEURL . 'account/">Account</a></li>
								<li><a href="' . HOMEURL . 'includes/logout.php">Logout</a></li>';
							
							} else {
							
								echo '<li><a href="#register" data-modaal-type="inline" class="modaal">Register</a></li>
								<li><a data-modaal-type="inline" class="modaal" href="#login">Login</a></li>';
							
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