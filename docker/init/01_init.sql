-- =====================================================
-- Base de datos: cafeteria_accesible
-- Reinicio completo orientado a IHC
-- =====================================================

DROP DATABASE IF EXISTS cafeteria_accesible;

CREATE DATABASE cafeteria_accesible
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE cafeteria_accesible;

-- =====================================================
-- USUARIOS
-- =====================================================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    cuenta VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100),
    apellido_materno VARCHAR(100),
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL
) ENGINE=InnoDB;

-- =====================================================
-- CONFIGURACIÓN DE USUARIO (tema claro / oscuro)
-- =====================================================
CREATE TABLE usuario_configuracion (
    id_usuario INT PRIMARY KEY,
    tema ENUM('claro','oscuro') DEFAULT 'claro',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB;

-- =====================================================
-- CATEGORÍAS
-- =====================================================
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    icono VARCHAR(100),
    orden INT DEFAULT 0
) ENGINE=InnoDB;

-- =====================================================
-- ALÉRGENOS
-- =====================================================
CREATE TABLE alergeno (
    id_alergeno INT AUTO_INCREMENT PRIMARY KEY,
    nombre_alergeno VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    icono VARCHAR(100)
) ENGINE=InnoDB;

-- =====================================================
-- PRODUCTOS
-- =====================================================
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_base DECIMAL(10,2) NOT NULL,
    id_categoria INT NOT NULL,
    imagen VARCHAR(255),
    disponible BOOLEAN DEFAULT TRUE,
    destacado BOOLEAN DEFAULT FALSE,
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
) ENGINE=InnoDB;

-- =====================================================
-- RELACIÓN PRODUCTO - ALÉRGENO
-- =====================================================
CREATE TABLE producto_alergeno (
    id_producto INT NOT NULL,
    id_alergeno INT NOT NULL,
    PRIMARY KEY (id_producto, id_alergeno),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_alergeno) REFERENCES alergeno(id_alergeno) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TAMAÑOS
-- =====================================================
CREATE TABLE tamanos (
    id_tamano INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tamano VARCHAR(50) NOT NULL,
    incremento_precio DECIMAL(10,2) DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE producto_tamano (
    id_producto INT NOT NULL,
    id_tamano INT NOT NULL,
    PRIMARY KEY (id_producto, id_tamano),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_tamano) REFERENCES tamanos(id_tamano) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- EXTRAS
-- =====================================================
CREATE TABLE extras (
    id_extra INT AUTO_INCREMENT PRIMARY KEY,
    nombre_extra VARCHAR(50) NOT NULL,
    precio_extra DECIMAL(10,2) DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE producto_extra (
    id_producto INT NOT NULL,
    id_extra INT NOT NULL,
    PRIMARY KEY (id_producto, id_extra),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_extra) REFERENCES extras(id_extra) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- CLIMAS (RECOMENDACIÓN)
-- =====================================================
CREATE TABLE climas (
    id_clima INT AUTO_INCREMENT PRIMARY KEY,
    nombre_clima VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE producto_clima (
    id_producto INT NOT NULL,
    id_clima INT NOT NULL,
    PRIMARY KEY (id_producto, id_clima),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_clima) REFERENCES climas(id_clima) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- PEDIDOS
-- =====================================================
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente','preparando','listo','entregado','cancelado') DEFAULT 'pendiente',
    metodo_pago VARCHAR(50),
    comentarios TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE detalle_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    id_tamano INT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2),
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    FOREIGN KEY (id_tamano) REFERENCES tamanos(id_tamano)
) ENGINE=InnoDB;

CREATE TABLE detalle_extra (
    id_detalle INT NOT NULL,
    id_extra INT NOT NULL,
    PRIMARY KEY (id_detalle, id_extra),
    FOREIGN KEY (id_detalle) REFERENCES detalle_pedido(id_detalle) ON DELETE CASCADE,
    FOREIGN KEY (id_extra) REFERENCES extras(id_extra)
) ENGINE=InnoDB;

-- =====================================================
-- PREFERENCIAS DEL USUARIO (HÁBITOS)
-- =====================================================
CREATE TABLE usuario_preferencias (
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    total_consumido INT DEFAULT 0,
    ultima_vez TIMESTAMP,
    PRIMARY KEY (id_usuario, id_producto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
) ENGINE=InnoDB;


-- =====================================================
-- DATOS INICIALES
-- =====================================================

-- -----------------------------------------------------
-- CATEGORÍAS
-- -----------------------------------------------------
INSERT INTO categorias (nombre_categoria, descripcion, icono, orden) VALUES
('Bebidas Calientes', 'Cafés, tés y chocolate', 'local_cafe', 1),
('Bebidas Frías', 'Frappés y bebidas con hielo', 'ac_unit', 2),
('Repostería', 'Pasteles y galletas', 'bakery_dining', 3),
('Comida', 'Sándwiches y snacks', 'lunch_dining', 4);

-- -----------------------------------------------------
-- ALÉRGENOS
-- -----------------------------------------------------
INSERT INTO alergeno (nombre_alergeno, descripcion, icono) VALUES
('Leche', 'Contiene derivados lácteos', 'local_drink'),
('Gluten', 'Contiene trigo o harinas', 'grass'),
('Frutos Secos', 'Contiene nueces o almendras', 'spa'),
('Chocolate', 'Contiene cacao', 'cookie');

-- -----------------------------------------------------
-- TAMAÑOS
-- -----------------------------------------------------
INSERT INTO tamanos (nombre_tamano, incremento_precio) VALUES
('Chico', 0),
('Mediano', 5),
('Grande', 10);

-- -----------------------------------------------------
-- EXTRAS
-- -----------------------------------------------------
INSERT INTO extras (nombre_extra, precio_extra) VALUES
('Chispas de chocolate', 5),
('Crema batida', 7),
('Leche deslactosada', 6),
('Shot extra de espresso', 8);

-- -----------------------------------------------------
-- PRODUCTOS
-- -----------------------------------------------------
INSERT INTO productos (nombre, descripcion, precio_base, id_categoria, imagen, destacado) VALUES
('Caviar Latte', 'Mezcla perfecta de espresso y leche.', 45.00, 1, 'caviar_latte.jpg', TRUE),
('Mocha Hazelnut', 'Chocolate con avellana.', 50.00, 1, 'mocha_hazelnut.jpg', TRUE),
('Coffee Frappuccino', 'Refrescante mezcla de café y hielo.', 55.00, 2, 'coffee_frapp.jpg', TRUE),
('Choco Cacao', 'Combinación intensa de cacao.', 48.00, 2, 'choco_cacao.jpg', TRUE),
('Cocoa Latte', 'Café y chocolate cremoso.', 52.00, 1, 'cocoa_latte.jpg', TRUE),
('Cocoa Frappuccino', 'Chocolate frío con hielo.', 58.00, 2, 'cocoa_frapp.jpg', FALSE),
('Espresso Simple', 'Café puro y concentrado.', 25.00, 1, 'espresso.jpg', FALSE),
('Té Verde', 'Infusión natural.', 30.00, 1, 'te_verde.jpg', FALSE),
('Sandwich de Pollo', 'Pan integral con pollo.', 65.00, 4, 'sandwich_pollo.jpg', TRUE),
('Galleta de Chocolate', 'Galleta artesanal con chispas.', 15.00, 3, 'galleta_choco.jpg', TRUE);

-- -----------------------------------------------------
-- RELACIÓN PRODUCTO - ALÉRGENOS
-- -----------------------------------------------------
-- Productos con leche
INSERT INTO producto_alergeno VALUES (1,1),(2,1),(5,1),(6,1);

-- Con gluten
INSERT INTO producto_alergeno VALUES (9,2),(10,2);

-- Con frutos secos
INSERT INTO producto_alergeno VALUES (2,3);

-- Con chocolate
INSERT INTO producto_alergeno VALUES (2,4),(4,4),(5,4),(6,4),(10,4);

-- -----------------------------------------------------
-- PRODUCTO - TAMAÑOS (solo bebidas)
-- -----------------------------------------------------
INSERT INTO producto_tamano
SELECT id_producto, id_tamano
FROM productos, tamanos
WHERE id_categoria IN (1,2);

-- -----------------------------------------------------
-- PRODUCTO - EXTRAS (solo bebidas)
-- -----------------------------------------------------
INSERT INTO producto_extra
SELECT id_producto, id_extra
FROM productos, extras
WHERE id_categoria IN (1,2);

-- -----------------------------------------------------
-- CLIMAS
-- -----------------------------------------------------
INSERT INTO climas (nombre_clima, descripcion) VALUES
('Caluroso', 'Ideal para bebidas frías'),
('Frío', 'Perfecto para bebidas calientes'),
('Lluvioso', 'Antojo de algo caliente'),
('Templado', 'Cualquier opción es buena');

-- Bebidas calientes → Frío y Lluvioso
INSERT INTO producto_clima
SELECT id_producto, 2 FROM productos WHERE id_categoria = 1;

INSERT INTO producto_clima
SELECT id_producto, 3 FROM productos WHERE id_categoria = 1;

-- Bebidas frías → Caluroso
INSERT INTO producto_clima
SELECT id_producto, 1 FROM productos WHERE id_categoria = 2;

-- Otros → Templado
INSERT INTO producto_clima
SELECT id_producto, 4 FROM productos WHERE id_categoria NOT IN (1,2);

-- -----------------------------------------------------
-- USUARIO DE PRUEBA
-- contraseña: admin123 (hash simulado)
-- -----------------------------------------------------
INSERT INTO usuarios (cuenta, nombre, apellido_paterno, correo, contrasena, telefono)
VALUES ('admin', 'Administrador', 'Sistema', 'admin@cafe.com',
'$2y$10$abcdefghijklmnopqrstuv', '5551234567');

INSERT INTO usuario_configuracion (id_usuario, tema)
VALUES (1, 'oscuro');

-- -----------------------------------------------------
-- PEDIDO DE EJEMPLO
-- -----------------------------------------------------
INSERT INTO pedidos (id_usuario, total, estado, metodo_pago)
VALUES (1, 60.00, 'entregado', 'Tarjeta');

INSERT INTO detalle_pedido (id_pedido, id_producto, id_tamano, cantidad, precio_unitario, subtotal)
VALUES (1, 1, 3, 1, 55.00, 55.00);

INSERT INTO detalle_extra VALUES (1, 2);

-- -----------------------------------------------------
-- PREFERENCIAS (simulación de hábito)
-- -----------------------------------------------------
INSERT INTO usuario_preferencias (id_usuario, id_producto, total_consumido, ultima_vez)
VALUES (1,1,5,NOW()),
       (1,3,3,NOW());

ALTER TABLE usuario_configuracion
ADD COLUMN idioma VARCHAR(10) DEFAULT 'es';

CREATE TABLE producto_traduccion (
    id_producto INT NOT NULL,
    idioma VARCHAR(10) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    PRIMARY KEY (id_producto, idioma),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

-- ============================================
-- MESAS
-- ============================================
CREATE TABLE mesas (
    id_mesa INT AUTO_INCREMENT PRIMARY KEY,
    numero_mesa INT NOT NULL UNIQUE,
    capacidad INT NOT NULL DEFAULT 6,
    disponible BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- Insertar 10 mesas
INSERT INTO mesas (numero_mesa, capacidad)
VALUES (1,6),(2,6),(3,6),(4,6),(5,6),
       (6,6),(7,6),(8,6),(9,6),(10,6);

-- ============================================
-- RESERVAS
-- ============================================
CREATE TABLE reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NULL,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    personas INT NOT NULL,
    estado ENUM('pendiente','confirmada','cancelada') DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB;

-- ============================================
-- RELACIÓN RESERVA - MESA
-- ============================================
CREATE TABLE reserva_mesa (
    id_reserva INT NOT NULL,
    id_mesa INT NOT NULL,
    PRIMARY KEY (id_reserva, id_mesa),
    FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva) ON DELETE CASCADE,
    FOREIGN KEY (id_mesa) REFERENCES mesas(id_mesa)
) ENGINE=InnoDB;


--- Cambios
ALTER TABLE usuarios 
ADD COLUMN rol ENUM('cliente','admin') DEFAULT 'cliente';

UPDATE usuarios 
SET rol = 'admin' 
WHERE cuenta = 'admin';

UPDATE categorias SET icono = 'mode_heat' WHERE id_categoria = 1;     -- Bebidas Calientes
UPDATE categorias SET icono = 'ac_unit' WHERE id_categoria = 2;       -- Bebidas Frías
UPDATE categorias SET icono = 'chef_hat' WHERE id_categoria = 3;      -- Repostería
UPDATE categorias SET icono = 'local_dining' WHERE id_categoria = 4;  -- Comida


-- Agregar campos a la tabla pedidos
ALTER TABLE pedidos 
ADD COLUMN tipo_entrega ENUM('pickup','delivery') DEFAULT 'pickup',
ADD COLUMN direccion TEXT NULL,
ADD COLUMN fecha_entrega DATE NULL,
ADD COLUMN hora_entrega TIME NULL,
ADD COLUMN metodo_pago_detalle TEXT NULL; -- para guardar info adicional (ej. CLABE, etc.)

-- Crear tabla para ingredientes eliminados (si quieres guardar preferencias)
CREATE TABLE detalle_ingrediente_eliminado (
    id_detalle INT NOT NULL,
    id_ingrediente INT NOT NULL, -- podrías referenciar una tabla de ingredientes o usar nombre
    PRIMARY KEY (id_detalle, id_ingrediente),
    FOREIGN KEY (id_detalle) REFERENCES detalle_pedido(id_detalle) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Nota: No tienes una tabla de ingredientes, así que podrías guardar como JSON en comentarios.
-- Por simplicidad, usaremos un campo JSON en detalle_pedido.
ALTER TABLE detalle_pedido ADD COLUMN personalizacion JSON NULL;

CREATE TABLE ingredientes (
    id_ingrediente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_ingrediente VARCHAR(100) NOT NULL
);
INSERT INTO ingredientes (nombre_ingrediente) VALUES
('Canela'),
('Azúcar'),
('Hielo'),
('Leche'),
('Pan'),
('Pollo'),
('Mayonesa'),
('Lechuga');

CREATE TABLE producto_ingrediente (
    id_producto INT,
    id_ingrediente INT,
    PRIMARY KEY (id_producto, id_ingrediente),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_ingrediente) REFERENCES ingredientes(id_ingrediente) ON DELETE CASCADE
);

-- Caviar Latte
INSERT INTO producto_ingrediente VALUES
(1,1),(1,2),(1,3),(1,4);

-- Sandwich
INSERT INTO producto_ingrediente VALUES
(9,5),(9,6),(9,7),(9,8);

CREATE TABLE producto_nutricion (
    id_producto INT PRIMARY KEY,
    kcal INT,
    grasas DECIMAL(5,2),
    azucares DECIMAL(5,2),
    proteina DECIMAL(5,2),
    sodio INT,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);


INSERT INTO producto_nutricion VALUES
(1,250,9,25,5,150),
(9,420,15,5,30,500);


TRUNCATE TABLE producto_extra;

INSERT INTO producto_extra VALUES
(1,1),(1,2),(1,3),(1,4),
(2,1),(2,2),(2,3),(2,4),
(3,1),(3,2),(3,4),
(4,1),(4,2),
(5,1),(5,2),(5,3),
(6,1),(6,2),
(7,4);

INSERT INTO producto_extra VALUES
(8,1),(8,2);
INSERT INTO producto_extra VALUES
(9,1);

INSERT INTO producto_extra VALUES
(10,1),
(11,1),(11,2);

TRUNCATE TABLE producto_tamano;
INSERT INTO producto_tamano VALUES
(1,1),(1,2),(1,3),
(2,1),(2,2),(2,3),
(3,1),(3,2),(3,3),
(4,1),(4,2),(4,3),
(5,1),(5,2),(5,3),
(6,1),(6,2),(6,3),
(7,1),(7,2),(7,3);


INSERT INTO producto_tamano VALUES
(8,1),(8,2),(8,3);

INSERT INTO producto_tamano VALUES
(9,1);

INSERT INTO producto_tamano VALUES
(10,1),
(11,1);

TRUNCATE TABLE producto_alergeno;

INSERT INTO producto_alergeno VALUES
(1,2),
(2,2),
(3,2),
(4,2),
(5,2),
(6,2),
(7,2),
(8,2),
(9,1),
(10,1),
(11,1);

ALTER TABLE producto_extra
ADD CONSTRAINT fk_producto_extra_producto
FOREIGN KEY (id_producto) REFERENCES productos(id_producto);

ALTER TABLE producto_extra
ADD CONSTRAINT fk_producto_extra_extra
FOREIGN KEY (id_extra) REFERENCES extras(id_extra);

-- 1. Agregar el Cheesecake (id 11) al clima Templado (id 4)
INSERT INTO producto_clima (id_producto, id_clima) 
VALUES (11, 4);

-- 2. Hacer que las Bebidas Calientes (cat 1) también se recomienden en Clima Templado (id 4)
-- Esto mejora la UX ya que el café se consume siempre.
INSERT INTO producto_clima (id_producto, id_clima)
SELECT id_producto, 4 FROM productos WHERE id_categoria = 1;

-- 3. Hacer que la Comida y Repostería (cat 3 y 4) aparezcan también en Clima Lluvioso (id 3)
-- "Día lluvioso = Café + Pan" es una regla de oro en IHC.
INSERT INTO producto_clima (id_producto, id_clima)
SELECT id_producto, 3 FROM productos WHERE id_categoria IN (3, 4);

-- 4. Limpieza: Si por error algún ID se duplicó, puedes verificar con:
-- SELECT id_producto, COUNT(*) FROM producto_clima GROUP BY id_producto, id_clima HAVING COUNT(*) > 1;