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

// Tasas de cambio fijas (1 MXN = X)
$currency_rates = [
    'es' => 1,
    'en' => 0.056,  // 1 MXN = 0.056 USD
    'de' => 0.048   // 1 MXN = 0.048 EUR
];

if (!function_exists('convert_currency')) {
    function convert_currency($amountMXN) {
        global $currency_rates;
        $lang = $_SESSION['idioma'] ?? 'es';
        $rate = $currency_rates[$lang] ?? 1;
        return $amountMXN * $rate;
    }
}

if (!function_exists('format_currency')) {
    function format_currency($amountMXN) {
        $lang = $_SESSION['idioma'] ?? 'es';
        $converted = convert_currency($amountMXN);
        $symbol = '';
        switch ($lang) {
            case 'es': $symbol = 'MXN'; break;
            case 'en': $symbol = 'USD'; break;
            case 'de': $symbol = 'EUR'; break;
            default: $symbol = 'MXN';
        }
        return '$' . number_format($converted, 2) . ' ' . $symbol;
    }
}
// Al final del archivo, antes de los cierres
if (!function_exists('format_size')) {
    function format_size($size_name) {
        $lang = $_SESSION['idioma'] ?? 'es';
        $sizes = [
            'Chico' => ['ml' => 240, 'oz' => 8],
            'Mediano' => ['ml' => 360, 'oz' => 12],
            'Grande' => ['ml' => 480, 'oz' => 16]
        ];
        $size = $sizes[$size_name] ?? ['ml' => 0, 'oz' => 0];
        
        if ($lang === 'en') {
            return $size['oz'] . ' fl oz';
        } else {
            return $size['ml'] . ' ml';
        }
    }
}
?>