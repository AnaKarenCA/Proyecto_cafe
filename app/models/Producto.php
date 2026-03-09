<?php
require_once __DIR__ . '/../../config/database.php';

class Producto {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    /* =====================================
       Obtener todos los productos
    ===================================== */
    public function getAll($idioma = 'es') {

    $sql = "
        SELECT 
            p.id_producto,
            p.id_categoria,
            COALESCE(pt.nombre, p.nombre) AS nombre,
            COALESCE(pt.descripcion, p.descripcion) AS descripcion,
            p.precio_base AS precio,
            p.imagen,
            p.disponible
        FROM productos p
        LEFT JOIN producto_traduccion pt
            ON p.id_producto = pt.id_producto
            AND pt.idioma = :idioma
        ORDER BY nombre ASC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['idioma' => $idioma]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /* =====================================
       Obtener producto por ID
    ===================================== */
    public function getById($id, $idioma = 'es') {

    $sql = "
        SELECT 
            p.id_producto,
            p.id_categoria,
            COALESCE(pt.nombre, p.nombre) AS nombre,
            COALESCE(pt.descripcion, p.descripcion) AS descripcion,
            p.precio_base AS precio,
            p.imagen,
            p.disponible
        FROM productos p
        LEFT JOIN producto_traduccion pt
            ON p.id_producto = pt.id_producto
            AND pt.idioma = :idioma
        WHERE p.id_producto = :id
        LIMIT 1
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'idioma' => $idioma
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    /* =====================================
       Productos destacados
       (primeros 6 por simplicidad)
    ===================================== */
    public function getDestacados($limite = 6, $idioma = 'es') {

    $sql = "
        SELECT 
            p.id_producto,
            p.id_categoria,
            COALESCE(pt.nombre, p.nombre) AS nombre,
            COALESCE(pt.descripcion, p.descripcion) AS descripcion,
            p.precio_base AS precio,
            p.imagen,
            p.disponible
        FROM productos p
        LEFT JOIN producto_traduccion pt
            ON p.id_producto = pt.id_producto
            AND pt.idioma = :idioma
        WHERE p.destacado = 1
        LIMIT :limite
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':idioma', $idioma);
    $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /* =====================================
       Productos por nombre de clima
    ===================================== */
    public function getPorClima($nombreClima, $idioma = 'es') {

    $sql = "
        SELECT 
            p.id_producto,
            p.id_categoria,
            COALESCE(pt.nombre, p.nombre) AS nombre,
            COALESCE(pt.descripcion, p.descripcion) AS descripcion,
            p.precio_base AS precio,
            p.imagen,
            p.disponible
        FROM productos p
        INNER JOIN producto_clima pc
            ON p.id_producto = pc.id_producto
        INNER JOIN climas c
            ON pc.id_clima = c.id_clima
        LEFT JOIN producto_traduccion pt
            ON p.id_producto = pt.id_producto
            AND pt.idioma = :idioma
        WHERE c.nombre_clima = :clima
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        'clima' => $nombreClima,
        'idioma' => $idioma
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getByCategoria($idCategoria, $idioma = 'es') {

    $sql = "
        SELECT 
            p.id_producto,
            p.id_categoria,
            COALESCE(pt.nombre, p.nombre) AS nombre,
            COALESCE(pt.descripcion, p.descripcion) AS descripcion,
            p.precio_base AS precio,
            p.imagen,
            p.disponible
        FROM productos p
        LEFT JOIN producto_traduccion pt
            ON p.id_producto = pt.id_producto
            AND pt.idioma = :idioma
        WHERE p.id_categoria = :idCategoria
        ORDER BY nombre ASC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        'idCategoria' => $idCategoria,
        'idioma' => $idioma
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}