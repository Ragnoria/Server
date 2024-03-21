<?php

namespace App\WebSocket\Entities;

use Ratchet\ConnectionInterface;

class ActionRouter
{
    /**
     * @param ConnectionInterface $connection
     * @param string $message
     * @return void
     */
    public static function resolve(ConnectionInterface $connection, string $message): void
    {
        if (!isset($connection->player) || !$connection->player) {
            return;
        }

        $action = static::getAction($message);
        $params = static::getParams($message);

        $action = new $action($connection->player, $params);
        $action->run();
    }

    /**
     * @param string $message
     * @return string
     */
    private static function getAction(string $message): string
    {
        $message = json_decode($message, true);
        $action = $message['action'] ?? null;

        if (!$action || !is_string($action) || preg_match('/[^a-z_\-0-9]/i', $action)) {
            throw new \InvalidArgumentException('Incorrect message structure.');
        }

        $action = strtolower(trim($action));
        if (!$actionClass = config('websockets.actions')[$action] ?? null) {
            throw new \InvalidArgumentException("'{$action}' is not recognized as an internal action.");
        }

        return $actionClass;
    }

    /**
     * @param string $message
     * @return array
     */
    private static function getParams(string $message): array
    {
        $message = json_decode($message, true);
        $params = $message['params'] ?? [];

        if (!is_array($params)) {
            throw new \InvalidArgumentException('Invalid parameters.');
        }

        return $params;
    }
}
