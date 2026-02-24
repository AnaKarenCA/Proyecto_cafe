<?php
require_once __DIR__ . '/../../config/database.php';

class Categoria {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Obtiene todas las categorías ordenadas
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categorias ORDER BY orden, nombre_categoria");
        return $stmt->fetchAll();
    }

    /**
     * Obtiene una categoría por su ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id_categoria = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crea una nueva categoría (para administración)
     */
    public function crear($nombre, $descripcion = '', $icono = '', $orden = 0) {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre_categoria, descripcion, icono, orden) VALUES (:nombre, :descripcion, :icono, :orden)");
        return $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'icono' => $icono,
            'orden' => $orden
        ]);
    }

    /**
     * Actualiza una categoría
     */
    public function actualizar($id, $nombre, $descripcion = '', $icono = '', $orden = 0) {
        $stmt = $this->db->prepare("UPDATE categorias SET nombre_categoria = :nombre, descripcion = :descripcion, icono = :icono, orden = :orden WHERE id_categoria = :id");
        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'icono' => $icono,
            'orden' => $orden
        ]);
    }

    /**
     * Elimina una categoría (si no tiene productos asociados)
     */
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM categorias WHERE id_categoria = :id");
        return $stmt->execute(['id' => $id]);
    }
}