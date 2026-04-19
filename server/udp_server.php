<?php

//php ka modul "sockets", na duhet me siguru qe bon
if (!extension_loaded('sockets')) {
    die("Gabim: Moduli 'sockets' nuk eshte aktivizuar!");
}

require_once 'config.php';
require_once 'client_manager.php';
require_once 'request_handler.php';

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

if (!$socket) {
    die("Nuk mund te krijohej socket-i: " . socket_strerror(socket_last_error()));
}

if (!socket_bind($socket, SERVER_IP, UDP_PORT)) {
    die("Nuk mund te lidhej socket-i: " . socket_strerror(socket_last_error()));
}

echo "--- UDP SERVER I NISUR ---" . PHP_EOL;
echo "Adresa: " . SERVER_IP . ":" . UDP_PORT . PHP_EOL;
echo "Direktoria e skedareve: " . STORAGE_DIR . PHP_EOL;
echo "--------------------------" . PHP_EOL;

while (true) {

    $bytes = socket_recvfrom($socket, $buffer, 2048, 0, $from_ip, $from_port);
    
    if ($bytes > 0) {
        $input = trim($buffer);

        if (!ClientManager::canAcceptNext($from_ip, $from_port)) {
            $errorMsg = "GABIM: Serveri ka arritur limitin prej 4 klientesh.";
            socket_sendto($socket, $errorMsg, strlen($errorMsg), 0, $from_ip, $from_port);
            echo "[REJECTED] Limiti u arrit per $from_ip:$from_port\n";
            continue;
        }

        ClientManager::track($from_ip, $from_port, $input);
        $response = RequestHandler::handle($input, $from_ip);
        socket_sendto($socket, $response, strlen($response), 0, $from_ip, $from_port);
    }
}
socket_close($socket);