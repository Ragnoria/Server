<?php

namespace App\WebSocket\Services;

use App\Models\Character;
use App\WebSocket\Entities\Player;
use App\WebSocket\Entities\World;
use Ratchet\ConnectionInterface;

class Transmit
{
    /**
     * @param Player|Player[] $players
     * @return self
     */
    public static function to(Player|array $players): self
    {
        if (!is_array($players)) {
            $players = [$players];
        }
        $connections = [];
        foreach ($players as $player) {
            $connections[] = $player->connection;
        }

        return new static($connections);
    }

    public static function nearby(Player|array $players): self
    {
        return Transmit::to($players);
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

    public function updateTile(array $position): self
    {
        return $this->withMessage('update-tiles', [
            'tiles' => [
                [
                    'position' => $position,
                    'stack' => World::getTile($position)->getStack()
                ]
            ]
        ]);
    }

    public function updateTiles(array $tiles): self
    {
        return $this->withMessage('update-tiles', [
            'tiles' => $tiles
        ]);
    }

    public function runEffect(string $effect, array $positions, bool $onCreature = false): self
    {
        return $this->withMessage('run-effect', [
            'effect' => $effect,
            'positions' => $positions,
            'onCreature' => $onCreature
        ]);
    }

    public function playSound(string $sound): self
    {
        return $this->withMessage('play-sound', [
            'id' => $sound,
        ]);
    }

    public function floatText(string $content, array $position, ?string $color = null): self
    {
        return $this->withMessage('float-text', [
            'content' => $content,
            'position' => $position,
            'color' => $color
        ]);
    }

    public function loot(int $itemId, int $quantity = 1): self
    {
        return $this->withMessage('loot', [
            'itemId' => $itemId,
            'quantity' => $quantity,
        ]);
    }

    public function updateInventory(): self
    {
        foreach ($this->connections as $connection) {
            foreach ($connection->player->inventory as $slot => $inventoryItem) {
                $this->withMessage('update-inventory-slot', [
                    'slot' => $slot,
                    'itemId' => $inventoryItem['itemId'],
                    'quantity' => $inventoryItem['quantity'],
                ]);
            }
        }

        return $this;
    }

    public function updateInventorySlot(string $slot, ?int $itemId, ?int $quantity): self
    {
        return $this->withMessage('update-inventory-slot', [
            'slot' => $slot,
            'itemId' => $itemId,
            'quantity' => $quantity,
        ]);
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
