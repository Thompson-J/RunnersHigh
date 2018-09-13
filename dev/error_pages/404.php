<?php

	include_once '../includes/functions.php';
	http_response_code(404);

?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Having an exercise routine is a factor in personal well-being, so I produced Runners’ High as a tool to aid enthusiast athletes during and after their runs. The project is the work of myself (josh-thompson.com), with design and computer development guidance from the tutors in the School of Art, Design and Architecture at University of Huddersfield.">
		<meta name="author" content="Josh Thompson">
		<meta name="keywords" content="josh, thompson, graphic, design, designer, student, front end, back end, web development, PHP, Javascript, jQuery, CSS, HTML, Adobe, CC, Muse, Illustrator, Photoshop, InDesign, runners high, endorphins, #hudGDA, University, of, Huddersfield, ADA, RFID, Processing, product, technology, digital, jogging, exercise"/>
		<link rel="icon" href="favicon.png">

		<title>Error 404|Runners&apos; High</title>
		
		<link href="/runnershigh/build/css/styles.css" rel="stylesheet" type="text/css">

	</head>

	<body>

		<nav class="sidenav">

			<a href="<?php echo HOMEURL ?>">Home</a>
		
		</nav>

		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>

			<main class="centre-container">

				<p class="error-message"><span class="emphasis">Error 404</span><br>
				You're lost, better go <a href="#" onclick="window.history.back();">back</a>.</p>

			</main>

		<?php include( $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php' ); ?>

	</body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/end-of-page.php'; ?>