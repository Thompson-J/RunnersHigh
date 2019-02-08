		<footer>

			<p>&copy; 2019 Runners&apos; High</p>
			<ul class="clearfix">
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
				
		</footer>

	</div>	

</body>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script src="https://www.googletagmanager.com/gtag/js?id=UA-108699977-2"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-108699977-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-108699977-3');
</script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<?php echo $addscripts; ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script type="text/javascript" src="<?php echo HOMEURL; ?>js/modaal.js"></script>
<script type="text/javascript" src="<?php echo HOMEURL; ?>js/sha512.js"></script>
<script type="text/javascript" src="<?php echo HOMEURL; ?>js/main.js"></script>

</html>