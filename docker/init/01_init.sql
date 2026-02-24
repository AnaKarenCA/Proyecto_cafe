-- =====================================================
-- Base de datos para el sistema de cafetería accesible
-- Nombre: cafeteria_accesible
-- Juego de caracteres: utf8mb4 (soporta emojis y acentos)
-- =====================================================
CREATE DATABASE IF NOT EXISTS cafeteria_accesible
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE cafeteria_accesible;

-- =====================================================
-- Tabla: usuarios
-- Almacena los datos de los clientes registrados.
-- Permite autenticación y asociación con pedidos/reservas.
-- =====================================================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    cuenta VARCHAR(50) NOT NULL UNIQUE COMMENT 'Nombre de usuario para login',
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100),
    apellido_materno VARCHAR(100),
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL COMMENT 'Hash de la contraseña (password_hash)',
    telefono VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL,
    INDEX idx_correo (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabla: categorias
-- Clasificación de los productos del menú.
-- =====================================================
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    icono VARCHAR(100) COMMENT 'Clase CSS o ruta del icono',
    orden INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabla: emociones (opcional)
-- Asocia productos con estados de ánimo para recomendaciones.
-- =====================================================
CREATE TABLE emociones (
    id_emocion INT AUTO_INCREMENT PRIMARY KEY,
    nombre_emocion VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabla: productos
-- Catálogo de productos de la cafetería.
-- =====================================================
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL CHECK (precio >= 0),
    id_categoria INT NOT NULL,
    id_emocion INT NULL,
    imagen VARCHAR(255) COMMENT 'Ruta de la imagen del producto',
    disponible BOOLEAN DEFAULT TRUE,
    destacado BOOLEAN DEFAULT FALSE COMMENT 'Para productos destacados en inicio',
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_emocion) REFERENCES emociones(id_emocion) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_categoria (id_categoria),
    INDEX idx_emocion (id_emocion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabla: reservas
-- Permite a usuarios (registrados o invitados) reservar mesa.
-- =====================================================
CREATE TABLE reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NULL COMMENT 'NULL si el cliente no está registrado',
    nombre_cliente VARCHAR(100) NOT NULL COMMENT 'Nombre de quien reserva',
    email_cliente VARCHAR(100) NOT NULL,
    telefono_cliente VARCHAR(20),
    fecha_reserva DATE NOT NULL,
    hora_reserva TIME NOT NULL,
    num_personas INT NOT NULL CHECK (num_personas > 0),
    comentarios TEXT,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_fecha (fecha_reserva),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabla: pedidos
-- Cabecera de los pedidos realizados (checkout).
-- =====================================================
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL COMMENT 'Usuario que realizó el pedido (debe estar registrado para pedidos)',
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL CHECK (total >= 0),
    estado ENUM('pendiente', 'preparando', 'listo', 'entregado', 'cancelado') DEFAULT 'pendiente',
    metodo_pago VARCHAR(50),
    comentarios TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_usuario (id_usuario),
    INDEX idx_estado_pedido (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabla: detalle_pedido
-- Líneas de detalle de cada pedido.
-- =====================================================
CREATE TABLE detalle_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL CHECK (cantidad > 0),
    precio_unitario DECIMAL(10,2) NOT NULL CHECK (precio_unitario >= 0),
    subtotal DECIMAL(10,2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_pedido (id_pedido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- INSERCIÓN DE DATOS INICIALES
-- =====================================================

-- Categorías (basadas en la imagen y necesidades comunes)
INSERT INTO categorias (nombre_categoria, descripcion, orden) VALUES
('Bebidas Calientes', 'Cafés, tés y chocolate', 1),
('Bebidas Frías', 'Frappés, smoothies, cold brew', 2),
('Repostería', 'Pasteles, galletas, muffins', 3),
('Comida', 'Sándwiches, ensaladas, snacks salados', 4),
('Especiales', 'Productos de temporada o edición limitada', 5);

-- Emociones (para posibles recomendaciones)
INSERT INTO emociones (nombre_emocion, descripcion) VALUES
('Feliz', 'Productos que transmiten alegría'),
('Cansado', 'Productos energizantes o reconfortantes'),
('Neutro', 'Productos para cualquier momento'),
('Relajado', 'Bebidas suaves y relajantes');

-- Productos (basados en la imagen: Signature Drinks y Best Sellers)
INSERT INTO productos (nombre, descripcion, precio, id_categoria, id_emocion, imagen, destacado) VALUES
('Caviar Latte', 'Una mezcla perfecta de espresso y leche. Disponible en dos tamaños.', 45.00, 1, 2, 'caviar_latte.jpg', TRUE),
('Mocha Hazelnut', 'Una rica y aterciopelada bebida de chocolate con avellana.', 50.00, 1, 1, 'mocha_hazelnut.jpg', TRUE),
('Coffee Frappuccino', 'Una refrescante mezcla de café y helado.', 55.00, 2, 1, 'coffee_frapp.jpg', TRUE),
('Choco Cacao', 'Deliciosa combinación de cacao y chocolate.', 48.00, 2, 3, 'choco_cacao.jpg', TRUE),
('Cocoa Latte', 'Una cremosa mezcla de café y chocolate.', 52.00, 1, 4, 'cocoa_latte.jpg', TRUE),
('Cocoa Frappuccino', 'Refrescante mezcla de café y chocolate con hielo.', 58.00, 2, 1, 'cocoa_frapp.jpg', FALSE),
('Espresso Simple', 'Café puro y concentrado.', 25.00, 1, 2, 'espresso.jpg', FALSE),
('Té Verde', 'Infusión natural de hojas de té verde.', 30.00, 1, 4, 'te_verde.jpg', FALSE),
('Sandwich de Pollo', 'Pan integral con pollo, lechuga y tomate.', 65.00, 4, 3, 'sandwich_pollo.jpg', TRUE),
('Galleta de Chocolate', 'Galleta artesanal con chispas de chocolate.', 15.00, 3, 1, 'galleta_choco.jpg', TRUE);

-- Usuario de prueba (contraseña: 'admin123' hasheada con password_hash)
-- El hash aquí es solo un ejemplo; en producción se debe generar con password_hash().
INSERT INTO usuarios (cuenta, nombre, apellido_paterno, correo, contrasena, telefono, activo)
VALUES ('admin', 'Administrador', 'Sistema', 'admin@cafe.com', '$2y$10$YourHashHere', '5551234567', TRUE);

-- NOTA: Reemplazar el hash por uno real generado con:
-- php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"

-- Reserva de ejemplo (opcional)
INSERT INTO reservas (id_usuario, nombre_cliente, email_cliente, telefono_cliente, fecha_reserva, hora_reserva, num_personas, comentarios, estado)
VALUES (1, 'Admin Prueba', 'admin@cafe.com', '5551234567', CURDATE() + INTERVAL 1 DAY, '13:00:00', 4, 'Mesa cerca de ventana', 'confirmada');

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================
-- =====================================================
-- Tabla: climas
-- =====================================================
CREATE TABLE climas (
    id_clima INT AUTO_INCREMENT PRIMARY KEY,
    nombre_clima VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabla: producto_clima (relación muchos a muchos)
-- =====================================================
CREATE TABLE producto_clima (
    id_producto INT NOT NULL,
    id_clima INT NOT NULL,
    PRIMARY KEY (id_producto, id_clima),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_clima) REFERENCES climas(id_clima) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar climas
INSERT INTO climas (nombre_clima, descripcion) VALUES
('Caluroso', 'Días de calor, ideales para bebidas frías'),
('Frío', 'Días fríos, perfectos para bebidas calientes'),
('Lluvioso', 'Días de lluvia, café caliente reconforta'),
('Templado', 'Clima agradable, cualquier bebida va bien');

-- Asociar productos existentes con climas (ejemplo)
-- Bebidas calientes (id_categoria 1) van con Frío y Lluvioso
INSERT INTO producto_clima (id_producto, id_clima)
SELECT id_producto, (SELECT id_clima FROM climas WHERE nombre_clima = 'Frío')
FROM productos WHERE id_categoria = 1;
INSERT INTO producto_clima (id_producto, id_clima)
SELECT id_producto, (SELECT id_clima FROM climas WHERE nombre_clima = 'Lluvioso')
FROM productos WHERE id_categoria = 1;

-- Bebidas frías (id_categoria 2) van con Caluroso
INSERT INTO producto_clima (id_producto, id_clima)
SELECT id_producto, (SELECT id_clima FROM climas WHERE nombre_clima = 'Caluroso')
FROM productos WHERE id_categoria = 2;

-- El resto va con Templado
INSERT INTO producto_clima (id_producto, id_clima)
SELECT id_producto, (SELECT id_clima FROM climas WHERE nombre_clima = 'Templado')
FROM productos WHERE id_categoria NOT IN (1,2);