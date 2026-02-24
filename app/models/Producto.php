<?php
require_once __DIR__ . '/../../config/database.php';

class Producto {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Obtiene todos los productos (solo disponibles)
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT p.*, c.nombre_categoria 
                                   FROM productos p 
                                   JOIN categorias c ON p.id_categoria = c.id_categoria 
                                   WHERE p.disponible = 1 
                                   ORDER BY p.destacado DESC, p.nombre");
        return $stmt->fetchAll();
    }

   

    /**
     * Obtiene un producto por su ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.nombre_categoria 
                                     FROM productos p 
                                     JOIN categorias c ON p.id_categoria = c.id_categoria 
                                     WHERE p.id_producto = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Busca productos por nombre o descripción (para búsqueda por texto/voz)
     */
    public function buscar($termino) {
        $termino = "%$termino%";
        $stmt = $this->db->prepare("SELECT p.*, c.nombre_categoria 
                                     FROM productos p 
                                     JOIN categorias c ON p.id_categoria = c.id_categoria 
                                     WHERE p.disponible = 1 
                                       AND (p.nombre LIKE :term OR p.descripcion LIKE :term)
                                     ORDER BY p.nombre");
        $stmt->execute(['term' => $termino]);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene productos destacados (para la página de inicio)
     */
    public function getDestacados($limite = 6) {
        $stmt = $this->db->prepare("SELECT p.*, c.nombre_categoria 
                                     FROM productos p 
                                     JOIN categorias c ON p.id_categoria = c.id_categoria 
                                     WHERE p.destacado = 1 AND p.disponible = 1
                                     ORDER BY p.nombre 
                                     LIMIT :limite");
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Crea un nuevo producto (administración)
     */
    public function crear($datos) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, id_categoria, id_emocion, imagen, disponible, destacado)
                VALUES (:nombre, :descripcion, :precio, :id_categoria, :id_emocion, :imagen, :disponible, :destacado)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
            'precio' => $datos['precio'],
            'id_categoria' => $datos['id_categoria'],
            'id_emocion' => $datos['id_emocion'] ?? null,
            'imagen' => $datos['imagen'] ?? null,
            'disponible' => $datos['disponible'] ?? 1,
            'destacado' => $datos['destacado'] ?? 0
        ]);
    }

    /**
     * Actualiza un producto
     */
    public function actualizar($id, $datos) {
        $sql = "UPDATE productos SET 
                nombre = :nombre,
                descripcion = :descripcion,
                precio = :precio,
                id_categoria = :id_categoria,
                id_emocion = :id_emocion,
                imagen = :imagen,
                disponible = :disponible,
                destacado = :destacado
                WHERE id_producto = :id";
        $stmt = $this->db->prepare($sql);
        $datos['id'] = $id;
        return $stmt->execute($datos);
    }

    /**
     * Elimina un producto (sólo si no tiene pedidos asociados)
     */
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id_producto = :id");
        return $stmt->execute(['id' => $id]);
    }
    /**
     * Obtiene productos más vendidos (basado en detalle_pedido)
     * @param int $limite
     * @return array
     */
    public function getMasVendidos($limite = 6) {
        $sql = "SELECT p.*, c.nombre_categoria, SUM(dp.cantidad) as total_vendido
                FROM productos p
                JOIN detalle_pedido dp ON p.id_producto = dp.id_producto
                JOIN categorias c ON p.id_categoria = c.id_categoria
                WHERE p.disponible = 1
                GROUP BY p.id_producto
                ORDER BY total_vendido DESC
                LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

/**
 * Obtiene productos por categoría con límite opcional
 */
public function getByCategoria($categoria_id, $limite = null) {
    $sql = "SELECT p.*, c.nombre_categoria 
            FROM productos p 
            JOIN categorias c ON p.id_categoria = c.id_categoria 
            WHERE p.id_categoria = :cat AND p.disponible = 1
            ORDER BY p.nombre";
    if ($limite) {
        $sql .= " LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cat', $categoria_id, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    } else {
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cat', $categoria_id, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}

    /**
     * Recomendaciones basadas en clima (simulado)
     */
    public function getRecomendacionesPorClima($clima) {
        switch ($clima) {
            case 'frio':
                return $this->getByCategoria(1, 4); // Bebidas calientes
            case 'calido':
                return $this->getByCategoria(2, 4); // Bebidas frías
            default:
                return [];
        }
    
    }
    /**
     * Obtiene productos por clima
     * @param string $clima (nombre del clima)
     * @return array
     */
    public function getPorClima($clima) {
        $sql = "SELECT p.*, c.nombre_categoria
                FROM productos p
                JOIN producto_clima pc ON p.id_producto = pc.id_producto
                JOIN climas cl ON pc.id_clima = cl.id_clima
                JOIN categorias c ON p.id_categoria = c.id_categoria
                WHERE cl.nombre_clima = :clima AND p.disponible = 1
                ORDER BY p.nombre
                LIMIT 6";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['clima' => $clima]);
        return $stmt->fetchAll();
    }
}