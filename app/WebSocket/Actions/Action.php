<?php

namespace App\WebSocket\Actions;

use App\WebSocket\Entities\Player;

abstract class Action
{
    public function __construct(
        public Player $player,
        public array $params = []
    )
    {
    }

    abstract public function run(): void;
}
