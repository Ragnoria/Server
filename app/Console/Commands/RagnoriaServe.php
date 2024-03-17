<?php

namespace App\Console\Commands;

use App\Services\Log;
use App\WebSocket\WebsocketHandler;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class RagnoriaServe extends Command
{
    protected $signature = 'ragnoria:serve';

    protected $description = 'Start ragnoria server';

    public function handle(): void
    {
        $host = config('websockets.host');
        $port = config('websockets.port');

        if (is_resource($fp = @fsockopen($host, $port))) {
            fclose($fp);
            Log::error("Socket $host:$port is already open!");
            exit();
        }

        app()->instance(IoServer::class, IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebsocketHandler
                )
            ),
            $port
        ));

        Log::success('Server started!');

        app()->get(IoServer::class)->run();
    }
}
