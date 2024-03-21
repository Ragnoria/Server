<?php

namespace App\WebSocket\Actions;

use App\WebSocket\Services\Transmit;

class UseObject extends Action
{
    public function run(): void
    {
        if (!$itemId = $this->params['itemId'] ?? null) {
            return;
        }

        match ($itemId) {
            8 => $this->oreHit(),
            default => null,
        };
    }

    private function oreHit(): void
    {
        if (!$position = $this->params['position'] ?? null) {
            return;
        }

        Transmit::player($this->player)
            ->playSound('mining')
            ->runEffect('ore-hit', [$position]);

        $dropChance = !rand(0,3);
        $dropQuantity = rand(1,3);

        if ($dropQuantity && $dropChance) {
            $slot = $this->getFirstSlotWithItem(10) ?? $this->getFirstSlotWithItem(null);
            $slotQuantity = ($slot['quantity'] ?? 0) + $dropQuantity;
            $this->player->inventory[$slot['slotId']] = ['itemId' => 10, 'quantity' => $slotQuantity];

            Transmit::player($this->player)
                ->updateInventorySlot($slot['slotId'], 10, $slotQuantity)
                ->loot(10, $dropQuantity);
        }
    }

    private function getFirstSlotWithItem(?int $itemId): ?array
    {
        foreach ($this->player->inventory as $slotId => $item) {
            if ($itemId == $item['itemId']) {
                return [
                    'slotId' => $slotId,
                    'quantity' => $item['quantity']
                ];
            }
        }

        return null;
    }
}
