<?php
if (!function_exists('__')) {
    function __($key, $default = null) {
        static $cache = [];
        $lang = $_SESSION['idioma'] ?? 'es';
        if (!isset($cache[$lang])) {
            $file = __DIR__ . "/../lang/{$lang}/messages.php";
            $cache[$lang] = file_exists($file) ? require $file : [];
        }
        return $cache[$lang][$key] ?? ($default ?? $key);
    }
}

if (!function_exists('get_tax_rate')) {
    function get_tax_rate() {
        $lang = $_SESSION['idioma'] ?? 'es';
        switch ($lang) {
            case 'es': return 0.16;
            case 'en': return 0.07;
            case 'de': return 0.19;
            default: return 0.16;
        }
    }
}

if (!function_exists('get_tax_name')) {
    function get_tax_name() {
        $lang = $_SESSION['idioma'] ?? 'es';
        switch ($lang) {
            case 'es': return 'IVA (16%)';
            case 'en': return 'Sales Tax (7%)';
            case 'de': return 'MwSt (19%)';
            default: return 'IVA';
        }
    }
}