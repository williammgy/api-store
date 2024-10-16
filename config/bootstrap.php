<?php declare(strict_types=1);

use App\Application\Shop\ListShopsHandler;
use App\Application\Shop\CreateShopHandler;
use App\Application\Shop\UpdateShopHandler;
use App\Application\Shop\DeleteShopHandler;
use App\Infrastructure\Database\DatabaseConnection;
use App\Infrastructure\Persistence\PDOShopRepository;
use App\Infrastructure\Http\Controller\ShopController;

use App\Infrastructure\Persistence\PDOTypeRepository;
use Dotenv\Dotenv;

header('Content-Type: application/json');

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');

    $dotenv->load();

    $dbConnection = new DatabaseConnection(
        getenv('DB_HOST'),
        getenv('DB_NAME'),
        getenv('DB_USER'),
        getenv('DB_PASSWORD')
    );

    $typeRepository = new PDOTypeRepository($dbConnection);
    $shopRepository = new PDOShopRepository($dbConnection, $typeRepository);

    $listShopsHandler = new ListShopsHandler($shopRepository);
    $createShopHandler = new CreateShopHandler($shopRepository, $typeRepository);
    $updateShopHandler = new UpdateShopHandler($shopRepository, $typeRepository);
    $deleteShopHandler = new DeleteShopHandler($shopRepository);

    $shopController = new ShopController(
        $listShopsHandler,
        $createShopHandler,
        $updateShopHandler,
        $deleteShopHandler
    );
} catch (Exception $e) {
    echo json_encode([
        'status'  => 500,
        'message' => $e->getMessage()
    ]);

    return;
}