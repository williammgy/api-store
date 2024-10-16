<?php declare(strict_types=1);

require_once 'bootstrap.php';

global $shopController;

$router = new AltoRouter();

$router->addMatchTypes([
    'uuid' => '[0-9a-fA-F\-]+'
]);

$router->map('GET', '/', function() {
    echo json_encode([
        'status'  => 200,
        'message' => 'Welcome to WSHOP'
    ]);

    return;
});

$router->addRoutes([
    ['GET', '/shops', function() use ($shopController) {
        $shopController->list($_GET);
    }],
    ['POST', '/shops', function() use ($shopController) {
        $shopController->create();
    }],
    ['PATCH', '/shops/[uuid:id]', function($id) use ($shopController) {
        $shopController->update($id);
    }],
    ['DELETE', '/shops/[uuid:id]', function($id) use ($shopController) {
        $shopController->delete($id);
    }]
]);