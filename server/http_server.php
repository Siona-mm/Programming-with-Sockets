<?php

require_once 'config.php';
require_once 'client_manager.php';

$http_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($http_socket, SOL_SOCKET, SO_REUSEADDR, 1);


if (!@socket_bind($http_socket, SERVER_IP, HTTP_PORT)) {
    die("GABIM: Porti " . HTTP_PORT . " eshte i zene ose i bllokuar. Ndrysho portin te config.php.\n");
}

socket_listen($http_socket, 5);
echo "HTTP Monitoruesi nisi ne http://" . SERVER_IP . ":" . HTTP_PORT . "/stats\n";

while (true) {
    $client = @socket_accept($http_socket);
    
    if ($client === false) continue; 

    $request = socket_read($client, 2048);

    if (strpos($request, 'GET /stats') !== false) {
       
        $stats = ClientManager::$history; 
        $body = json_encode($stats, JSON_PRETTY_PRINT);
        
        $response = "HTTP/1.1 200 OK\r\n";
        $response .= "Content-Type: application/json\r\n";
        $response .= "Content-Length: " . strlen($body) . "\r\n";
        $response .= "Connection: close\r\n\r\n";
        $response .= $body;
    } else {
        $response = "HTTP/1.1 404 Not Found\r\n\r\nFaqja nuk ekziston.";
    }

    socket_write($client, $response);
    socket_close($client);
}