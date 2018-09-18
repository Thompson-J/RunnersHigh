	<footer>

		<p>&copy; 2018 Runners&apos; High</p>
		<ul class="clearfix">
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
			
	</footer>

</div>