<?php

namespace App\WebSocket\Entities;

class Tile
{
    public function removeItem(int $itemId): void
    {

    }

    public function getStack(): array
    {
        return [
            ['id' => 1, 'quantity' => 1]
        ];
    }
}
