<?php

class FileManager {
    
    
    public static function listFiles($dir) {
        if (!is_dir($dir)) return "Direktoria nuk ekziston.";
        
        $files = array_diff(scandir($dir), array('.', '..'));
        return count($files) > 0 ? implode(', ', $files) : "Direktoria eshte e zbrazet.";
    }

    
    public static function readFile($dir, $filename) {
        $path = $dir . $filename;
        
        if (file_exists($path)) {
            if (is_file($path)) {
                return file_get_contents($path);
            }
            return "Gabim: Emri i dhene eshte direktori.";
        }
        
        return "Gabim: Skedari '$filename' nuk u gjend.";
    }

    
    public static function getInfo($dir, $filename) {
        $path = $dir . $filename;
        if (file_exists($path)) {
            $size = filesize($path);
            $modified = date("d-m-Y H:i:s", filemtime($path));
            return "Madhesia: $size bytes | Modifikuar: $modified";
        }
        return "Gabim: Skedari nuk u gjend.";
    }

    public static function searchFiles($dir, $keyword) {
        $files = array_diff(scandir($dir), array('.', '..'));
        $found = [];
        foreach ($files as $file) {
            if (stripos($file, $keyword) !== false) {
                $found[] = $file;
            }
        }
        return count($found) > 0 
            ? "Rezultatet për '$keyword': " . implode(', ', $found) 
            : "Nuk u gjet asnjë skedar që përmban '$keyword'.";
    }

    public static function dummyUpload($dir, $filename) {
        $path = $dir . $filename;
        $content = "File i krijuar nga admin më " . date('Y-m-d H:i:s') . "\nPërmbajtje dummy për testim.";
        file_put_contents($path, $content);
        return "SUKSES: Skedari '$filename' u ngarkua (dummy).";
    }

    public static function deleteFile($dir, $filename) {
        $path = $dir . $filename;
        if (file_exists($path)) {
            unlink($path);
            return "SUKSES: Skedari '$filename' u fshi.";
        }
        return "Gabim: Skedari '$filename' nuk ekziston.";
    }

    public static function dummyDownload($dir, $filename) {
        $path = $dir . $filename;
        if (file_exists($path) && is_file($path)) {
            $content = file_get_contents($path);
            return "DOWNLOAD_CONTENT:$filename\n" . $content;
        }
        return "Gabim: Skedari '$filename' nuk u gjet për shkarkim.";
    }
}