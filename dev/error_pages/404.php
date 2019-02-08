<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
http_response_code(404);

$title = 'Error 404 Not Found' . TITLE_DELIMITER . TITLE;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

?>

			<main class="centre-container">

				<p class="error-message"><span class="emphasis">Error 404</span><br>
				You're lost better go <a href="#" onclick="window.history.back();">back</a>.</p>

			</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/end_of_page.php'; ?>