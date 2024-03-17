<?php

namespace App\WebSocket\Actions;

class RequestTiles extends Action
{
    public function run(): void
    {
        $tiles = [];
        foreach ($this->params['positions'] as $position) {
            $stack = [];

            if (!rand(0,40)) {
                $stack[] = ['id' => 2, 'quantity' => 1];
            } elseif (!rand(0,40)) {
                $stack[] = ['id' => 3, 'quantity' => 1];
            } elseif (!rand(0,30)) {
                $stack[] = ['id' => 4, 'quantity' => 1];
            } else {
                $stack[] = ['id' => 1, 'quantity' => 1];
            }

            if (!rand(0,100)) {
                $stack[] = ['id' => 6, 'quantity' => 1];
            } elseif (!rand(0,100)) {
                $stack[] = ['id' => 8, 'quantity' => 1];
            } elseif (!rand(0,100)) {
                $stack[] = ['id' => 5, 'quantity' => 1];
            } elseif (!rand(0,100)) {
                $stack[] = ['id' => 7, 'quantity' => 1];
            } elseif (!rand(0,100)) {
                $stack[] = ['id' => 12, 'quantity' => 1];
            }

            $tiles[] = [
                'position' => $position,
                'stack' => $stack
            ];
        }

        $this->player->connection->send(json_encode([
            'event' => 'update-tiles',
            'params' => [
                'tiles' => $tiles
            ]
        ]));
    }
}
