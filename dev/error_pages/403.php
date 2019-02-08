<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
http_response_code(403);

$title = 'Error 403 Not Found' . TITLE_DELIMITER . TITLE;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

?>

			<main class="centre-container">

				<p class="error-message"><span class="emphasis">Error 403</span><br>
				You haven't got permission to run here better head <a href="#" onclick="window.history.back();">back</a>.</p>

			</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/end_of_page.php'; ?>