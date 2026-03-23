<?php
require_once __DIR__ . '/../../config/weather.php';

class WeatherHelper {

    /**
     * Devuelve la ciudad y coordenadas según el idioma de la sesión (fallback)
     * @param string $lang 'es', 'en', 'de'
     * @return array ['city' => string, 'lat' => float, 'lon' => float]
     */
    public static function getCityByLanguage($lang) {
        $cities = [
            'es' => ['city' => 'Toluca', 'lat' => 19.29, 'lon' => -99.65],
            'en' => ['city' => 'New York', 'lat' => 40.71, 'lon' => -74.01],
            'de' => ['city' => 'Berlin', 'lat' => 52.52, 'lon' => 13.40]
        ];
        return $cities[$lang] ?? $cities['es'];
    }

    /**
     * Obtiene el clima actual usando coordenadas (o las de la sesión/idioma)
     * @param float|null $lat
     * @param float|null $lon
     * @return array|null Datos del clima o null si falla
     */
    public static function getCurrentWeather($lat = null, $lon = null) {
        // Usar coordenadas de sesión si existen y no se pasan explícitamente
        if ($lat === null && isset($_SESSION['user_lat']) && isset($_SESSION['user_lon'])) {
            $lat = $_SESSION['user_lat'];
            $lon = $_SESSION['user_lon'];
        }

        // Si aún no hay coordenadas, usar las del idioma
        if ($lat === null) {
            $city = self::getCityByLanguage($_SESSION['idioma'] ?? 'es');
            $lat = $city['lat'];
            $lon = $city['lon'];
        }

        // Caché en sesión (expira 1 hora)
        $cacheKey = "weather_{$lat}_{$lon}";
        if (isset($_SESSION[$cacheKey]) && $_SESSION[$cacheKey]['expires'] > time()) {
            return $_SESSION[$cacheKey]['data'];
        }

        $url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units=metric&appid=" . OPENWEATHER_API_KEY;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            error_log("Error al consultar OpenWeather: HTTP $httpCode");
            return null;
        }

        $data = json_decode($response, true);
        // Guardar en caché por 1 hora
        $_SESSION[$cacheKey] = [
            'data' => $data,
            'expires' => time() + 3600
        ];
        return $data;
    }

    /**
     * Convierte los datos del clima a un nombre de clima de la base de datos
     * @param array $weatherData
     * @return string 'Caluroso'|'Frío'|'Lluvioso'|'Templado'
     */
    public static function getClimateName($weatherData) {
        if (!$weatherData) {
            return 'Templado';
        }
        $main = $weatherData['weather'][0]['main'] ?? '';
        $temp = $weatherData['main']['temp'] ?? 20;

        if (in_array($main, ['Rain', 'Drizzle', 'Thunderstorm'])) {
            return 'Lluvioso';
        }
        if (in_array($main, ['Snow', 'Mist', 'Fog'])) {
            return 'Frío';
        }
        if ($temp >= 25) {
            return 'Caluroso';
        }
        if ($temp <= 15) {
            return 'Frío';
        }
        return 'Templado';
    }

    /**
     * Determina el tipo de clima para la tarjeta CSS
     * @param array $weatherData
     * @return string 'sunny'|'cloudy'|'rainy'|'snowy'|'night'
     */
    public static function getWeatherType($weatherData) {
        if (!$weatherData) {
            return 'sunny';
        }
        $icon = $weatherData['weather'][0]['icon'] ?? '01d';
        $main = $weatherData['weather'][0]['main'] ?? '';

        if (strpos($icon, 'n') !== false) {
            return 'night';
        }
        if (in_array($main, ['Rain', 'Drizzle', 'Thunderstorm'])) {
            return 'rainy';
        }
        if (in_array($main, ['Snow'])) {
            return 'snowy';
        }
        if (in_array($main, ['Clouds'])) {
            return 'cloudy';
        }
        return 'sunny';
    }
}