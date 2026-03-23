<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../helpers/WeatherHelper.php';

class WelcomeController {
    private $productoModel;
    private $categoriaModel;

    public function __construct() {
        $this->productoModel = new Producto();
        $this->categoriaModel = new Categoria();
    }

    private function getIdioma() {
        return $_SESSION['idioma'] ?? 'es';
    }

    public function index() {
        $idioma = $this->getIdioma();
        $hasLocation = isset($_SESSION['user_lat']) && isset($_SESSION['user_lon']);
        // Productos más vendidos (top 10)
        $productos_destacados = $this->productoModel->getMasVendidos(10, $idioma);
        $categorias = $this->categoriaModel->getAll();

        // Obtener clima actual (usa coordenadas de sesión si existen)
        $weatherData = WeatherHelper::getCurrentWeather();
        $nombreClima = WeatherHelper::getClimateName($weatherData);
        $recomendaciones_clima = $this->productoModel->getPorClima($nombreClima, $idioma);

        // Si no hay productos para ese clima, usar los más vendidos como fallback
        if (empty($recomendaciones_clima)) {
            $recomendaciones_clima = $this->productoModel->getMasVendidos(6, $idioma);
        }

        $carrusel_items = $recomendaciones_clima;

        // Datos para la tarjeta de clima
        $ciudad = $weatherData['name'] ?? 'Ubicación desconocida';
        $temp = $weatherData ? round($weatherData['main']['temp']) : null;
        $desc = $weatherData ? $weatherData['weather'][0]['description'] : null;
        $weatherType = WeatherHelper::getWeatherType($weatherData);

        $titulo = "Bienvenido a Omnis Café";
        // Después de obtener $nombreClima = WeatherHelper::getClimateName($weatherData);
$nombreClimaTraducido = __($nombreClima);
// O usar la clave con prefijo:
$nombreClimaTraducido = __('clima_' . $nombreClima);
// Pasar a la vista como $nombreClima (o una variable separada).
$nombreClima = $nombreClimaTraducido;

        require_once __DIR__ . '/../views/welcome.php';
    }

    /**
     * AJAX: Guarda las coordenadas del usuario en sesión
     */
    public function setLocation() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['lat']) && isset($input['lon'])) {
            $_SESSION['user_lat'] = floatval($input['lat']);
            $_SESSION['user_lon'] = floatval($input['lon']);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Faltan coordenadas']);
        }
    }
}