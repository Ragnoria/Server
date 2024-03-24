<?php

namespace App\WebSocket\Actions;

use App\WebSocket\Services\Transmit;

class RequestTiles extends Action
{
    public function run(): void
    {
        $tiles = $this->generateTiles($this->params['positions']);

        Transmit::to($this->player)
            ->updateTiles($tiles);
    }

    private function generateTiles(array $positions): array
    {
        $tiles = [];
        foreach ($positions as $position) {
            $stack = [];

            if (rand(0, 10)) {
                $stack[] = ['id' => 1, 'quantity' => 1];
            } else {
                $stack[] = ['id' => 2, 'quantity' => 1];
            }

            if (!rand(0, 100)) {
                $stack[] = ['id' => 6, 'quantity' => 1];
            } elseif (!rand(0, 100)) {
                $stack[] = ['id' => 8, 'quantity' => 1];
            } elseif (!rand(0, 100)) {
                $stack[] = ['id' => 5, 'quantity' => 1];
            } elseif (!rand(0, 100)) {
                $stack[] = ['id' => 7, 'quantity' => 1];
            } elseif (!rand(0, 100)) {
                $stack[] = ['id' => 12, 'quantity' => 1];
            }

            $tiles[] = [
                'position' => $position,
                'stack' => $stack
            ];
        }

        return $tiles;
    }
}
