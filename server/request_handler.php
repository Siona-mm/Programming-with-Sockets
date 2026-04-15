<?php
require_once 'config.php';
require_once 'file_manager.php';

class RequestHandler {
    // DUHET te jete public static qe te thirret me ::
    public static function handle($input, $ip) {
        $parts = explode(' ', trim($input));
        $command = $parts[0];
        $arg = isset($parts[1]) ? $parts[1] : null;

        // Kontrolli i Adminit (Pika 6)
        $isAdmin = ($ip === '127.0.0.1' || $ip === '::1');

        switch ($command) {
            case '/list':
                return "Files: " . FileManager::listFiles(STORAGE_DIR);
            
            case '/read':
                if (!$arg) return "Gabim: Jepni emrin e skedarit. (/read file.txt)";
                return FileManager::readFile(STORAGE_DIR, $arg);

            case '/upload':
                if (!$isAdmin) return "GABIM: Vetem Admini mund te ngarkoje skedare.";
                if (!$arg) return "Gabim: Jepni emrin e skedarit.";
                file_put_contents(STORAGE_DIR . $arg, "Data e krijuar ne " . date('H:i:s'));
                return "SUKSES: Skedari u ngarkua.";

            case '/delete':
                if (!$isAdmin) return "GABIM: Vetem Admini mund te fshije skedare.";
                if (!$arg) return "Gabim: Jepni emrin e skedarit.";
                if (file_exists(STORAGE_DIR . $arg)) {
                    unlink(STORAGE_DIR . $arg);
                    return "SUKSES: Skedari u fshie.";
                }
                return "GABIM: Skedari nuk u gjend.";

            default:
                return "Serveri: Komande e panjohur ose mesazh i thjeshte.";
        }
    }
}