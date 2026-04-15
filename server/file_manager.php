<?php

class FileManager {
    
    // Metoda per listimin e skedareve
    public static function listFiles($dir) {
        if (!is_dir($dir)) return "Direktoria nuk ekziston.";
        
        $files = array_diff(scandir($dir), array('.', '..'));
        return count($files) > 0 ? implode(', ', $files) : "Direktoria eshte e zbrazet.";
    }

    // Metoda qe mungonte: readFile
    public static function readFile($dir, $filename) {
        $path = $dir . $filename;
        
        if (file_exists($path)) {
            // Kontrollon nese eshte skedar dhe jo direktori
            if (is_file($path)) {
                return file_get_contents($path);
            }
            return "Gabim: Emri i dhene eshte direktori.";
        }
        
        return "Gabim: Skedari '$filename' nuk u gjend.";
    }

    // Metoda per informacionin e skedarit (opsionale nese e therrisni te Handler)
    public static function getInfo($dir, $filename) {
        $path = $dir . $filename;
        if (file_exists($path)) {
            $size = filesize($path);
            $modified = date("d-m-Y H:i:s", filemtime($path));
            return "Madhesia: $size bytes | Modifikuar: $modified";
        }
        return "Gabim: Skedari nuk u gjend.";
    }
}