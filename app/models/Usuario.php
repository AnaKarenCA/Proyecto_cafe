<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    public static function registrar($nombre, $email, $password) {
        $db = Database::connect();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :pass)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $hash);
        return $stmt->execute();
    }

    public static function login($email, $password) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch();
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    public static function getById($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT id, nombre, email FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}