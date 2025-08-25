<?php
function send_socket_message($message) {
    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($sock === false) {
        log_message('error', 'Socket create failed: ' . socket_strerror(socket_last_error()));
        return false;
    }
    $result = socket_connect($sock, HOST, '9090');
    if ($result === false) {
        log_message('error', 'Socket connect failed: ' . socket_strerror(socket_last_error($sock)));
        socket_close($sock);
        return false;
    }
    $payload = is_array($message) ? json_encode($message) : $message;
    socket_write($sock, $payload, strlen($payload));
    socket_close($sock);
    return true;
}
// send_socket_message([
            // 'type' => 'show_news',
            // 'news' => $data
        // ]);
