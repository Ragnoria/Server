<?php

namespace App\WebSocket\Entities;

use App\Models\Character;
use App\WebSocket\Exceptions\AuthException;
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
            World::attachPlayer(new Player($character, $connection));
        }
    }

    public static function disconnect(ConnectionInterface $connection): void
    {
        if (!$connection->player ?? false) {
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
