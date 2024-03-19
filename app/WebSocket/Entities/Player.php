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

        Transmit::player($player)->init($character);
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
