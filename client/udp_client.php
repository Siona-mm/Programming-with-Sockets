<?php
require_once __DIR__ . '/../server/config.php';

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

echo "--- UDP CLIENT ---\n";
echo "Komandat: /list, /read, /upload, /download, /delete, /search, /info\n";
echo "Shkruaj 'quit' ose 'exit' për të dalë.\n\n";

while (true) {
    $msg = readline("Shkruaj komanden: ");
    
    if (strtolower(trim($msg)) === "quit" || strtolower(trim($msg)) === "exit") {
        break;
    }
    
    if (empty(trim($msg))) continue;

    socket_sendto($socket, $msg, strlen($msg), 0, SERVER_IP, UDP_PORT);

    socket_recvfrom($socket, $reply, 4096, 0, $from_ip, $from_port);
    echo "Serveri ktheu: " . $reply . "\n\n";
}

socket_close($socket);
echo "Klienti u mbyll me sukses.\n";