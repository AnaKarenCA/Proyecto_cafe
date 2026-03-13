<?php
require_once __DIR__ . '/../models/UsuarioConfiguracion.php';

class LanguageController
{
    public function change()
    {
        // Obtener el idioma solicitado
        $lang = $_GET['lang'] ?? 'es';
        $allowed = ['es', 'en', 'de'];

        // Validar que sea uno de los permitidos
        if (in_array($lang, $allowed)) {
            // Guardar en sesión
            $_SESSION['idioma'] = $lang;

            // Si el usuario está logueado, guardar preferencia en base de datos
            if (isset($_SESSION['usuario_id'])) {
                $configModel = new UsuarioConfiguracion();
                $configModel->setIdioma($_SESSION['usuario_id'], $lang);
            }
        }

        // Redirigir a la página anterior (o al inicio si no hay referer)
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header("Location: $referer");
        exit;
    }
}