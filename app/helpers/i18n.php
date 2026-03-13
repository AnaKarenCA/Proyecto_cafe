<?php
if (!function_exists('__')) {
    function __($key, $default = null) {
        static $translations = null;
        
        if ($translations === null) {
            $lang = $_SESSION['idioma'] ?? 'es'; // Idioma actual
            $file = __DIR__ . "/../lang/{$lang}/messages.php";
            if (file_exists($file)) {
                $translations = require $file;
            } else {
                $translations = []; // Fallback vacío
            }
        }
        
        return $translations[$key] ?? ($default ?? $key);
    }
}