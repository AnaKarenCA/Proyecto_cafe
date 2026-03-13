<?php
require_once __DIR__ . '/../../config/database.php';

class UsuarioConfiguracion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Guarda o actualiza el idioma del usuario
     */
    public function setIdioma($id_usuario, $idioma)
    {
        $sql = "INSERT INTO usuario_configuracion (id_usuario, idioma) 
                VALUES (:id, :idioma)
                ON DUPLICATE KEY UPDATE idioma = :idioma";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id_usuario, 'idioma' => $idioma]);
    }

    /**
     * Obtiene la configuración de un usuario
     */
    public function getById($id_usuario)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario_configuracion WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}