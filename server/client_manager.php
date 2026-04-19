<?php

class ClientManager {
    public static $history = [];
    public static $active_clients = [];
    private static $limit = 4;

    public static function canAcceptNext($ip, $port) {
        $clientId = "$ip:$port";
        $currentTime = time();
        $timeout = 60;

        // Fshin klientët që kanë kaluar kohën e timeout-it
        foreach (self::$active_clients as $id => $lastActiveTime) {
            if (($currentTime - $lastActiveTime) > $timeout) {
                unset(self::$active_clients[$id]);
            }
        }

        // Nëse klienti ekziston, përditëso kohën dhe lejoje

        if (array_key_exists($clientId, self::$active_clients)) {
            self::$active_clients[$clientId] = $currentTime;
            return true;
        }

        // Kontrollon limitin prej 4 klientësh
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

       // Shkruan aktivitetin në logs.txt
        file_put_contents(__DIR__ . '/logs.txt', 
            "[" . date('H:i:s') . "] $ip:$port -> $message" . PHP_EOL, 
            FILE_APPEND);
    }

        public static function getStats() {
        return [
            'active_clients' => count(self::$active_clients),
            'total_messages' => count(self::$history),
            'history'        => array_slice(self::$history, 0, 20),
            'storage_dir'    => STORAGE_DIR
        ];
    }
}