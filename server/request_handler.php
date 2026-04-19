<?php
require_once 'config.php';
require_once 'file_manager.php';

class RequestHandler {
    
    public static function handle($input, $ip) {
        $parts = explode(' ', trim($input), 2);
        $command = strtolower($parts[0] ?? '');
        $arg = $parts[1] ?? null;

        
        $isAdmin = ($ip === '127.0.0.1' || $ip === '::1');

        switch ($command) {
            case '/list':
                return FileManager::listFiles(STORAGE_DIR);
            
            case '/read':
                if (!$arg) return "Gabim: Jepni emrin e skedarit. (/read file.txt)";
                return FileManager::readFile(STORAGE_DIR, $arg);

            case '/upload':
                if (!$isAdmin) return "GABIM: Vetem Admini mund te ngarkoje skedare.";
                if (!$arg) return "Gabim: Jepni emrin e skedarit.";
                return FileManager::dummyUpload(STORAGE_DIR, $arg);

            case '/download':
                if (!$arg) return "Gabim: Jepni emrin e skedarit. (/download file.txt)";
                return FileManager::dummyDownload(STORAGE_DIR, $arg);

            case '/delete':
                if (!$isAdmin) return "GABIM: Vetem Admini mund te fshije skedare.";
                if (!$arg) return "Gabim: Jepni emrin e skedarit.";
                return FileManager::deleteFile(STORAGE_DIR, $arg);

            case '/search':
                if (!$arg) return "Gabim: Jepni fjalen kyce. (/search keyword)";
                return FileManager::searchFiles(STORAGE_DIR, $arg);

            case '/info':
                if (!$arg) return "Gabim: Jepni emrin e skedarit. (/info file.txt)";
                return FileManager::getInfo(STORAGE_DIR, $arg);

            default:
                return "Serveri: Komande e panjohur ose mesazh i thjeshte.";
        }
    }
}