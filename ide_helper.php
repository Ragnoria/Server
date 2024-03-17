<?php

namespace Ratchet {

    use App\WebSocket\Entities\Player;

    /**
     * @see \Ratchet\ConnectionInterface
     * @property Player $player
     * @property \GuzzleHttp\Psr7\Request $httpRequest
     * @property string $remoteAddress;
     * @property string $resourceId;
     */
    class ConnectionInterface {}
}
