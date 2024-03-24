<?php

namespace App\WebSocket\Entities;

use App\Models\Character;
use App\Services\Log;

class World
{
    /** @var Player[] */
    private static array $players = [];

    public static function getPlayerByCharacter(Character $character): ?Player
    {
        return static::$players[$character->id] ?? null;
    }

    public static function attachPlayer(Player $player): void
    {
        static::$players[$player->character->id] = $player;

        Log::info(strtr("Player {name} joined game world. Players online: {online}.", [
            '{name}' => $player->character->name,
            '{online}' => count(static::$players)
        ]));
    }

    public static function detachPlayer(Player $player): void
    {
        unset(static::$players[$player->character->id]);
        $player->character->save();

        Log::info(strtr("Player {name} left game world. Players online: {online}.", [
            '{name}' => $player->character->name,
            '{online}' => count(static::$players)
        ]));
    }

    public static function getTile(array $position): Tile
    {
        return new Tile;
    }
}
