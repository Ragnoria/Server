<?php

return [
    'protocol' => env('WEBSOCKET_PROTOCOL', 'ws'),
    'host' => env('WEBSOCKET_HOST', '127.0.0.1'),
    'port' => env('WEBSOCKET_PORT', 6001),
];
