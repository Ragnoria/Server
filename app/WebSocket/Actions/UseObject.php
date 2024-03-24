<?php

namespace App\WebSocket\Actions;

use App\WebSocket\Entities\Item;
use App\WebSocket\Entities\World;
use App\WebSocket\Services\Transmit;
use Illuminate\Support\Arr;

class UseObject extends Action
{
    public function run(): void
    {
        match ($this->getItem()) {
            Item::CHEST => $this->openChest(),
            Item::ORE_VEIN => $this->mine(),
            default => null,
        };
    }

    protected function openChest(): void
    {
        $receivedItem = Arr::random([Item::HEALTH_POTION, Item::MANA_POTION, Item::PLATE_ARMOR]);

        $this->player->addItemToInventory($receivedItem);

        World::getTile($this->getPosition())
            ->removeItem(Item::CHEST);

        Transmit::to($this->player)
            ->updateInventory()
            ->loot($receivedItem);

        Transmit::nearby($this->player)
            ->playSound('chest')
            ->updateTile($this->getPosition())
            ->runEffect('yellow-sparkles', [$this->getPosition()])
            ->floatText('Ohh, nice!', $this->getPosition(), '#fff0a5');
    }

    protected function mine(): void
    {
        Transmit::to($this->player)
            ->playSound('mining')
            ->runEffect('ore-hit', [$this->getPosition()]);

        $dropChance = !rand(0,3);
        $dropQuantity = rand(1,3);

        if ($dropQuantity && $dropChance) {
            $this->player->addItemToInventory(Item::ORE, $dropQuantity);

            Transmit::to($this->player)
                ->updateInventory()
                ->floatText('+3 exp', $this->getPosition())
                ->loot(Item::ORE, $dropQuantity);
        }
    }

    private function getItem(): ?int
    {
        return $this->params['itemId'] ?? null;
    }

    private function getPosition(): ?array
    {
        return $this->params['position'] ?? null;
    }

    public function validate(): bool
    {
        if (!$this->getItem() || !$this->getPosition()) {
            return false;
        }

        return true;
    }
}
