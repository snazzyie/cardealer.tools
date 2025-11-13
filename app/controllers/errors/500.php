<?php
/**
 * 500 Internal Server Error
 */

http_response_code(500);

$page_title = '500 - Internal Server Error';
$error_message = 'Something went wrong on our end. Please try again later.';

require BASE_PATH . 'views/errors/error.php';
