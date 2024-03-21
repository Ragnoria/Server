<?php

namespace App\WebSocket\Entities;

use App\Models\Character;
use App\WebSocket\Exceptions\AuthException;
use App\WebSocket\Services\Transmit;
use Ratchet\ConnectionInterface;

class Player
{
    public Character $character;

    public ConnectionInterface $connection;

    public array $inventory = [
        'inventory-1' => ['itemId' => null, 'quantity' => null],
        'inventory-2' => ['itemId' => null, 'quantity' => null],
        'inventory-3' => ['itemId' => null, 'quantity' => null],
        'inventory-4' => ['itemId' => null, 'quantity' => null],
        'inventory-5' => ['itemId' => null, 'quantity' => null],
        'inventory-6' => ['itemId' => null, 'quantity' => null],
        'inventory-7' => ['itemId' => null, 'quantity' => null],
        'inventory-8' => ['itemId' => null, 'quantity' => null],
        'inventory-9' => ['itemId' => null, 'quantity' => null],
        'inventory-10' => ['itemId' => null, 'quantity' => null],
        'inventory-11' => ['itemId' => null, 'quantity' => null],
        'inventory-12' => ['itemId' => null, 'quantity' => null],
        'inventory-13' => ['itemId' => null, 'quantity' => null],
        'inventory-14' => ['itemId' => null, 'quantity' => null],
        'inventory-15' => ['itemId' => null, 'quantity' => null],
        'inventory-16' => ['itemId' => null, 'quantity' => null],
        'inventory-17' => ['itemId' => null, 'quantity' => null],
        'inventory-18' => ['itemId' => null, 'quantity' => null],
        'inventory-19' => ['itemId' => null, 'quantity' => null],
        'inventory-20' => ['itemId' => null, 'quantity' => null],
        'inventory-21' => ['itemId' => null, 'quantity' => null],
        'inventory-22' => ['itemId' => null, 'quantity' => null],
        'inventory-23' => ['itemId' => null, 'quantity' => null],
        'inventory-24' => ['itemId' => null, 'quantity' => null],
    ];

    public static function connect(ConnectionInterface $connection): void
    {
        $token = $connection->httpRequest->getUri()->getQuery();
        if (!$character = Character::where(['token' => $token])->first()) {
            throw new AuthException;
        }

        if ($player = World::getPlayerByCharacter($character)) {
            $player->swapConnection($connection);
        } else {
            $player = new Player($character, $connection);
            World::attachPlayer($player);
        }

        Transmit::player($player)
            ->init($character)
            ->runEffect('energy', [['x' => 100, 'y' => 100]], true)
            ->playSound('login');
    }

    public static function disconnect(ConnectionInterface $connection): void
    {
        if (!isset($connection->player) || !$connection->player) {
            return;
        }

        World::detachPlayer($connection->player);
    }

    private function __construct(Character $character, ConnectionInterface $connection)
    {
        $this->character = $character;
        $this->connection = $connection;
        $this->connection->player = $this;
    }

    private function swapConnection(ConnectionInterface $connection): void
    {
        $this->connection->player = null;
        $this->connection->close();

        $this->connection = $connection;
        $this->connection->player = $this;
    }
}
