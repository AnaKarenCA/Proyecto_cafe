<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    /* ================================
       Obtener usuario por cuenta o correo
    ================================= */
    public function getByCuenta($cuenta) {

        $sql = "SELECT * FROM usuarios 
                WHERE cuenta = :cuenta OR correo = :cuenta
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cuenta' => $cuenta]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ================================
       Verificar si cuenta existe
    ================================= */
    public function existeCuenta($cuenta) {

        $sql = "SELECT COUNT(*) FROM usuarios WHERE cuenta = :cuenta";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cuenta' => $cuenta]);

        return $stmt->fetchColumn() > 0;
    }

    /* ================================
       Verificar si correo existe
    ================================= */
    public function existeCorreo($correo) {

        $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['correo' => $correo]);

        return $stmt->fetchColumn() > 0;
    }

    /* ================================
       Crear usuario (devuelve ID)
    ================================= */
    public function crear($datos) {

        $sql = "INSERT INTO usuarios 
                (cuenta, nombre, apellido_paterno, apellido_materno, correo, contrasena, telefono, activo, fecha_registro)
                VALUES
                (:cuenta, :nombre, :apellido_paterno, :apellido_materno, :correo, :contrasena, :telefono, 1, NOW())";

        $stmt = $this->pdo->prepare($sql);

        $ok = $stmt->execute([
            'cuenta' => $datos['cuenta'],
            'nombre' => $datos['nombre'],
            'apellido_paterno' => $datos['apellido_paterno'],
            'apellido_materno' => $datos['apellido_materno'],
            'correo' => $datos['correo'],
            'contrasena' => $datos['contrasena'],
            'telefono' => $datos['telefono']
        ]);

        if ($ok) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }

    /* ================================
       Obtener idioma
    ================================= */
    public function getIdioma($id_usuario) {

        $sql = "SELECT idioma 
                FROM usuario_configuracion 
                WHERE id_usuario = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id_usuario]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $data['idioma'] : 'es';
    }

    /* ================================
       Guardar idioma
    ================================= */
    public function setIdioma($id_usuario, $idioma) {

        $sql = "
            INSERT INTO usuario_configuracion (id_usuario, idioma)
            VALUES (:id, :idioma)
            ON DUPLICATE KEY UPDATE idioma = :idioma
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id_usuario,
            'idioma' => $idioma
        ]);
    }
    public function getById($id) {
    $sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}