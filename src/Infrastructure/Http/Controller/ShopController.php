<?php declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\Shop\CreateShopHandler;
use App\Application\Shop\DeleteShopHandler;
use App\Application\Shop\ListShopsHandler;
use App\Application\Shop\UpdateShopHandler;

class ShopController
{
    public function __construct(
        private ListShopsHandler $listShopsHandler,
        private CreateShopHandler $createShopHandler,
        private UpdateShopHandler $updateShopHandler,
        private DeleteShopHandler $deleteShopHandler
    ) {}

    public function list(array $filters): void
    {
        $shops = $this->listShopsHandler->handle($filters);

        echo json_encode($shops);

        return;
    }

    public function create(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $this->createShopHandler->handle($data);

        echo json_encode([
            'status'  => 201,
            'message' => 'Shop successfully created'
        ]);

        return;
    }

    public function update(string $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $this->updateShopHandler->handle($id, $data);

        echo json_encode([
            'status'  => 200,
            'message' => 'Shop successfully updated'
        ]);

        return;
    }

    public function delete(string $id): void
    {
        $this->deleteShopHandler->handle($id);

        echo json_encode([
            'status'  => 200,
            'message' => 'Shop successfully deleted'
        ]);

        return;
    }
}