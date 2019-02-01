

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

		<div class="input-rule" id="register_bad_email"></div>
		<input id="register_email" type="email" name="register_email" placeholder="Email…" required>

		<div class="input-rule" id="register_bad_password"></div>
		<input id="register_password" type="password" name="register_password" placeholder="Password…" required>

		<div class="input-rule" id="register_bad_password_cnfrm"></div>
		<input id="register_password_cnfrm" type="password" name="register_password_cnfrm" placeholder="Confirm password…" required>

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