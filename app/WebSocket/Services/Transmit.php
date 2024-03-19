<?php

namespace App\WebSocket\Services;

use App\Models\Character;
use App\WebSocket\Entities\Player;
use Ratchet\ConnectionInterface;

class Transmit
{
    public static function player(Player $player): self
    {
        return new static([$player->connection]);
    }

    /**
     * @param ConnectionInterface[] $connections
     */
    private function __construct(private readonly array $connections)
    {
    }

    public function init(Character $character): self
    {
        return $this->withMessage('init', [
            'name' => $character->name,
            'x' => 100,
            'y' => 100,
        ]);
    }

    public function updateTiles(array $tiles): self
    {
        return $this->withMessage('update-tiles', ['tiles' => $tiles]);
    }

    public function withMessage(string $event, array $params = []): self
    {
        foreach ($this->connections as $connection) {
            $connection->send(json_encode([
                'event' => $event,
                'params' => $params
            ]));
        }

        return $this;
    }
}
