<?php

namespace App\WebSocket\Actions;

class Ping extends Action
{
    public function run(): void
    {
        $this->player->connection->send('PONG');
    }
}
