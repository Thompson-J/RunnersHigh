<script type="text/javascript">
	// When the page has loaded
	$(function() {
		// Submit forms with the ENTER key
		$('input').keydown(function(e) {

			if (e.keyCode == 13) {
				$(this).parent().children('button').click();
			}

		});
	});
</script>

<h1>Login</h1>

<form id="login-form">

	<div class="input-rule" id="bad-email"></div>
	<input type="email" id="email" name="email" autofocus="autofocus" placeholder="Email…" required>
	
	<div class="input-rule" id="bad-password"></div>
	<input type="password" id="password" name="password" placeholder="Password…" required>
	
	<label class="remember-user">
		<input type="checkbox" id="remember-user" name="remember-user">
		<span>Remember user?</span>
	</label>
	
	<button type="button" onclick="retrieveActivity()">Login</button>

	<p id="reset-link"><a href="#" onclick="passwordResetModal()">Reset your password&hellip;</a></p>

</form>

