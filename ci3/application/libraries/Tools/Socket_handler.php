<?php 
include_once(dirname(__DIR__, 3) . '/vendor/autoload.php');
$lockFile = dirname(__DIR__, 4) . '/socket.lock';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
function socket_log($msg) {
    global $lockFile;
    $msg = "[" . date('Y-m-d H:i:s') . "] " . $msg . "\n";
    file_put_contents($lockFile, $msg, FILE_APPEND);
}
class Socket implements MessageComponentInterface {
    protected $clients;
    public function __construct() {
        $this->clients = new \SplObjectStorage;
        socket_log("Socket server initialized.");
    }
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        socket_log("New connection: {$conn->resourceId}");
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
        socket_log("Message from {$from->resourceId}: $msg");
    }
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        socket_log("Connection {$conn->resourceId} closed");
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        socket_log("Error: {$e->getMessage()}");
        $conn->close();
    }
    public function broadcast($msg) {
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
        socket_log("Broadcast: $msg");
    }
}
class Socket_handler {
    private static $socketInstance;
    public static function getSocket() {
        if (!self::$socketInstance) {
            self::$socketInstance = new Socket();
        }
        return self::$socketInstance;
    }
    public function run() {
        $server = IoServer::factory(
            new HttpServer(new WsServer(self::getSocket())),
            9090
        );
        socket_log("WebSocket server running on ws://0.0.0.0:9090");
        $server->run();
    }
    public function broadcast($msg) {
        self::getSocket()->broadcast($msg);
    }
}
if (php_sapi_name() === 'cli') {
    file_put_contents($lockFile, "");
    $server = new Socket_handler();
    $server->run();
}