<?php

return [
    'protocol' => env('WEBSOCKET_PROTOCOL', 'ws'),
    'host' => env('WEBSOCKET_HOST', '127.0.0.1'),
    'port' => env('WEBSOCKET_PORT', 6001),
    'actions' => [
        'request-tiles' => \App\WebSocket\Actions\RequestTiles::class,
        'use-object' => \App\WebSocket\Actions\UseObject::class,
    ]
];
