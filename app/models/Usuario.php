<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Obtiene un usuario por su cuenta (nombre de usuario)
     */
    public function getByCuenta($cuenta) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE cuenta = :cuenta AND activo = 1");
        $stmt->execute(['cuenta' => $cuenta]);
        return $stmt->fetch();
    }

    /**
     * Obtiene un usuario por su ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Verifica si ya existe una cuenta
     */
    public function existeCuenta($cuenta) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE cuenta = :cuenta");
        $stmt->execute(['cuenta' => $cuenta]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica si ya existe un correo
     */
    public function existeCorreo($correo) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Crea un nuevo usuario
     * @param array $datos Campos: cuenta, nombre, apellido_paterno, apellido_materno, correo, contrasena, telefono
     * @return bool|int ID del nuevo usuario o false
     */
    public function crear($datos) {
        $sql = "INSERT INTO usuarios (cuenta, nombre, apellido_paterno, apellido_materno, correo, contrasena, telefono)
                VALUES (:cuenta, :nombre, :apellido_paterno, :apellido_materno, :correo, :contrasena, :telefono)";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            'cuenta' => $datos['cuenta'],
            'nombre' => $datos['nombre'],
            'apellido_paterno' => $datos['apellido_paterno'] ?? null,
            'apellido_materno' => $datos['apellido_materno'] ?? null,
            'correo' => $datos['correo'],
            'contrasena' => $datos['contrasena'],
            'telefono' => $datos['telefono'] ?? null
        ]);
        if ($success) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Actualiza el último acceso del usuario
     */
    public function actualizarUltimoAcceso($id) {
        $stmt = $this->db->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id_usuario = :id");
        return $stmt->execute(['id' => $id]);
    }
}