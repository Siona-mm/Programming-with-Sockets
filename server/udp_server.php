<?php


//php ka modul "sockets", na duhet me siguru qe bon
if (!extension_loaded('sockets')) {
    die("Gabim: Moduli 'sockets' nuk eshte aktivizuar!");
}

//mi require fajllat qe na nevojiten
require_once 'config.php';
require_once 'client_manager.php';
require_once 'request_handler.php';

//pe bojna socketin UDP (funksion i gatshem)
$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

//nese ska mujt me kriju e qesum qit error
if (!$socket) {
    die("Nuk mund te krijohej socket-i: " . socket_strerror(socket_last_error()));
}

// qito tdhana i kena marr prej config.php edhe e kena bo bind portin e udp me ip e serverit
//nese ska bo i kena qu error
if (!socket_bind($socket, SERVER_IP, UDP_PORT)) {
    die("Nuk mund te lidhej socket-i: " . socket_strerror(socket_last_error()));
}

//kto jon veq outputat
echo "--- UDP SERVER I NISUR ---" . PHP_EOL;
echo "Adresa: " . SERVER_IP . ":" . UDP_PORT . PHP_EOL;
echo "Direktoria e skedareve: " . STORAGE_DIR . PHP_EOL;
echo "--------------------------" . PHP_EOL;

//qiky loop pret mesazhe prej klientit
while (true) {

    // nqit variabel ruhen mesazhet qe vijn prej klientit ip edhe porti prej ku e qon klienti mesazihn
    $bytes = socket_recvfrom($socket, $buffer, 2048, 0, $from_ip, $from_port);
    
    if ($bytes > 0) {
        $input = trim($buffer);

        // limitin e klientave e kena lan 4
        //e merr klasen prej client_manager.php dhe e therras funksionin canAcceptNext 
        //nese spranon ma shume i qet error mesazh
        if (!ClientManager::canAcceptNext($from_ip, $from_port)) {
            $errorMsg = "GABIM: Serveri ka arritur limitin prej 4 klientesh.";
            socket_sendto($socket, $errorMsg, strlen($errorMsg), 0, $from_ip, $from_port);
            echo "[REJECTED] Limiti u arrit per $from_ip:$from_port\n";
            continue; // qe mos me pranu kerkesen, nuk mjafton veq mesazhi
        }

        //apet prej client_manager.php 
        //i regjistron klientat edhe ja menaxhon kerkesat
        //e qon responsin tek klienti me socket_sendto
        ClientManager::track($from_ip, $from_port, $input);
        $response = RequestHandler::handle($input, $from_ip);
        socket_sendto($socket, $response, strlen($response), 0, $from_ip, $from_port);
    }
}
socket_close($socket);