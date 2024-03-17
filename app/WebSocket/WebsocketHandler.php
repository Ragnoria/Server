<?php

namespace App\WebSocket;

use App\Services\Log;
use App\WebSocket\Entities\ActionRouter;
use App\WebSocket\Entities\Player;
use App\WebSocket\Exceptions\AuthException;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use Ratchet\WebSocket\WsConnection;

class WebsocketHandler implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface|WsConnection $conn): void
    {
        try {
            Player::connect($conn);
        } catch (AuthException $e) {
            $conn->close();
        } catch (\Throwable $t) {
            Log::error($t);
        }
    }

    public function onClose(ConnectionInterface|WsConnection $conn): void
    {
        try {
            Player::disconnect($conn);
        } catch (\Throwable $t) {
            Log::error($t);
        }
    }

    public function onError(ConnectionInterface|WsConnection $conn, \Exception $e): void
    {
        Log::error($e);
    }

    public function onMessage(ConnectionInterface|WsConnection $conn, $msg): void
    {
        try {
            ActionRouter::resolve($conn, $msg);
        } catch (\InvalidArgumentException $e) {
            Log::warning($e->getMessage());
        } catch (\Throwable $t) {
            Log::error($t);
        }
    }
}
