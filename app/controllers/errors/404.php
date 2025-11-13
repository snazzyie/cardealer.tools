<?php
/**
 * 404 Not Found Error
 */

http_response_code(404);

$page_title = '404 - Page Not Found';
$error_message = 'The page you are looking for could not be found.';

require BASE_PATH . 'views/errors/error.php';
