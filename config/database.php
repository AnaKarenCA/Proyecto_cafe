<?php
class Database {
private static $host = "127.0.0.1";
private static $port = 3307;
private static $db   = "cafeteria_accesible";
    private static $user = "cafe_user";
    private static $pass = "cafe_pass";


    public static function connect() {
        try {
            return new PDO(
                "mysql:host=" . self::$host .
                ";dbname=" . self::$db .
                ";port=" . self::$port .
                ";charset=utf8mb4",   //  ACENTOS
                self::$user,
                self::$pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("❌ Error de conexión BD: " . $e->getMessage());
        }
    }
}
?>