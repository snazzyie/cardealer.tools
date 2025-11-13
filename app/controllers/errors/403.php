<?php
/**
 * 403 Forbidden Error
 */

http_response_code(403);

$page_title = '403 - Access Denied';
$error_message = 'You do not have permission to access this resource.';

require BASE_PATH . 'views/errors/error.php';
