<?php

namespace App\Http;

use App\Services\Log;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use Ratchet\WebSocket\WsConnection;

class WebsocketHandler implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface|WsConnection $conn): void
    {
        Log::info('New connection.');
        $conn->send('Hello Client!');
    }

    public function onClose(ConnectionInterface|WsConnection $conn): void
    {
        Log::info('Connection closed.');
    }

    public function onError(ConnectionInterface|WsConnection $conn, \Exception $e): void
    {
        Log::info('An error occurred: ' . $e->getMessage());
    }

    public function onMessage(ConnectionInterface|WsConnection $conn, $msg): void
    {
        Log::info('Message received.');
    }
}
