<?php
include_once( __DIR__ . '/../../../vendor/autoload.php');
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;  
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
class Socket_handler implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface $conn) {
        echo "New connection: ({$conn->resourceId})\n";
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Message from {$from->resourceId}: $msg\n";
        foreach ($from->httpRequest->getHeaders() as $header => $value) {
            echo "$header: $value\n";
        }
    }
    public function onClose(ConnectionInterface $conn) {
        echo "Connection closed: ({$conn->resourceId})\n";
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Socket_handler()
        )
    ),
    8080
);
echo "WebSocket server started on port 8080\n";
$server->run();