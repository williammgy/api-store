<?php declare(strict_types=1);

require_once '../vendor/autoload.php';
require_once '../config/routes.php';

$match = $router->match();

if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');

    echo json_encode([
        'status'  => 404,
        'message' => 'Not found'
    ]);

    return;
}