<?php

class ClientManager {
    public static $history = [];
    public static $active_clients = [];
    private static $limit = 4;

    public static function canAcceptNext($ip, $port) {
        $clientId = "$ip:$port";
        $currentTime = time();
        $timeout = 60;

        // Remove inactive clients (timeout)
        foreach (self::$active_clients as $id => $lastActiveTime) {
            if (($currentTime - $lastActiveTime) > $timeout) {
                unset(self::$active_clients[$id]);
            }
        }

        if (array_key_exists($clientId, self::$active_clients)) {
            self::$active_clients[$clientId] = $currentTime;
            return true;
        }

        if (count(self::$active_clients) >= self::$limit) {
            return false;
        }

        self::$active_clients[$clientId] = $currentTime;
        return true;
    }

    public static function track($ip, $port, $message) {
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'client_ip' => $ip,
            'client_port' => $port,
            'message' => $message
        ];

        array_unshift(self::$history, $entry);

       
        file_put_contents(__DIR__ . '/logs.txt', 
            "[" . date('H:i:s') . "] $ip:$port -> $message" . PHP_EOL, 
            FILE_APPEND);
    }
}