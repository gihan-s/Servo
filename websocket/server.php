<?php

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);


require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/ChatServer.php'; // include ChatServer file

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080 // WebSocket port
);

$server->run();
