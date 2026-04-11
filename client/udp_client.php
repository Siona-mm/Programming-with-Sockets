<?php

$host = '127.0.0.1'; 
$port = 9090;      

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

echo "--- UDP STANDARD CLIENT (Read Only) ---\n";
echo "Mund te perdorni: /list, /read, /search, /info\n";
echo "Shenim: Ju nuk keni privilegje per /upload ose /delete\n\n";

while (true) {
    $message = readline("Pyetje per serverin: ");
    
    if (empty($message)) continue;
    if ($message == "exit") break;

    socket_sendto($socket, $message, strlen($message), 0, $host, $port);

    socket_recvfrom($socket, $response, 2048, 0, $from_ip, $from_port);
    
    echo "Serveri: " . $response . "\n------------------------\n";
}

socket_close($socket);