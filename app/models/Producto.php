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
/* =====================================
   CREAR PRODUCTO
===================================== */
public function crear($nombre, $descripcion, $precio, $categoria, $imagen)
    {
        $sql = "INSERT INTO productos
                (nombre, descripcion, precio_base, id_categoria, imagen)
                VALUES (:nombre, :descripcion, :precio, :categoria, :imagen)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'categoria' => $categoria,
            'imagen' => $imagen
        ]);
    }


/* =====================================
   ACTUALIZAR PRODUCTO
===================================== */
 public function actualizar($id, $nombre, $descripcion, $precio, $categoria, $imagen)
    {
        $sql = "UPDATE productos
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    precio_base = :precio,
                    id_categoria = :categoria,
                    imagen = :imagen
                WHERE id_producto = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'categoria' => $categoria,
            'imagen' => $imagen
        ]);
    }


/* =====================================
   ELIMINAR PRODUCTO
===================================== */
public function eliminar($id)
    {
        $sql = "DELETE FROM productos WHERE id_producto = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    // ... (código existente)

    /* =====================================
       Productos más vendidos (top 10)
    ===================================== */
    public function getMasVendidos($limite = 10, $idioma = 'es') {
        $sql = "
            SELECT 
                p.id_producto,
                p.id_categoria,
                COALESCE(pt.nombre, p.nombre) AS nombre,
                COALESCE(pt.descripcion, p.descripcion) AS descripcion,
                p.precio_base AS precio,
                p.imagen,
                p.disponible,
                SUM(dp.cantidad) AS total_vendido
            FROM productos p
            LEFT JOIN detalle_pedido dp ON p.id_producto = dp.id_producto
            LEFT JOIN producto_traduccion pt ON p.id_producto = pt.id_producto AND pt.idioma = :idioma
            GROUP BY p.id_producto
            ORDER BY total_vendido DESC
            LIMIT :limite
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idioma', $idioma);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Dentro de la clase Producto, añadir:

public function getAllergenIds($producto_id) {
    $stmt = $this->pdo->prepare("SELECT id_alergeno FROM producto_alergeno WHERE id_producto = :id");
    $stmt->execute(['id' => $producto_id]);
    return implode(',', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

public function getClimateIds($producto_id) {
    $stmt = $this->pdo->prepare("SELECT id_clima FROM producto_clima WHERE id_producto = :id");
    $stmt->execute(['id' => $producto_id]);
    return implode(',', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

public function getSizeIds($producto_id) {
    $stmt = $this->pdo->prepare("SELECT id_tamano FROM producto_tamano WHERE id_producto = :id");
    $stmt->execute(['id' => $producto_id]);
    return implode(',', $stmt->fetchAll(PDO::FETCH_COLUMN));
}
// Dentro de la clase Producto, añadir al final:

public function getIngredientes($producto_id) {
    $sql = "SELECT i.id_ingrediente, i.nombre_ingrediente
            FROM ingredientes i
            INNER JOIN producto_ingrediente pi ON i.id_ingrediente = pi.id_ingrediente
            WHERE pi.id_producto = :id
            ORDER BY i.nombre_ingrediente";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id' => $producto_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}