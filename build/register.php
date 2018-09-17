<?php
include_once 'includes/functions.php';

sec_session_start();

?>

<script type="text/javascript" src="<?php echo HOMEURL . 'js/register.js' ?>"></script>


<h1>Register</h1>

<form id="register-form">
	
	<div class="input-rule" id="bad-name"></div>
	<input id="name" type="text" name="fullname" placeholder="Name…" required>

	<div class="input-rule" id="bad-email"></div>
	<input id="email" type="email" name="email" placeholder="Email…" required>

	<div class="input-rule" id="bad-password"></div>
	<input id="password" type="password" name="password" placeholder="Password…" required>

	<div class="input-rule" id="bad-password-confirmation"></div>
	<input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm password…" required>

	<!-- Added type="button" to change default button behaviour -->
	<button type="button" id="submit">Submit</button>

</form>