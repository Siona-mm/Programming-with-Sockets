<?php
require_once __DIR__ . '/../server/config.php';

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

echo "--- UDP ADMIN CLIENT ---\n";
echo "Komandat: /list, /read <file>, /upload <file>\n";

while (true) {
    $msg = readline("Shkruaj komanden: ");
    if ($msg == "quit") break;

    socket_sendto($socket, $msg, strlen($msg), 0, SERVER_IP, UDP_PORT);

    socket_recvfrom($socket, $reply, 2048, 0, $from_ip, $from_port);
    echo "Serveri ktheu: " . $reply . "\n\n";
}

socket_close($socket);
