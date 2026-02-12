-- --------------------------------------------------------
-- Base de datos: cafeteria_escolar
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS cafeteria_escolar 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE cafeteria_escolar;

-- Configurar la sesión para UTF8
SET NAMES utf8mb4;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de categorías
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    alergenos VARCHAR(100), 
    destacado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de reservas
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    personas INT NOT NULL,
    tipo_mesa ENUM('redonda', 'cuadrada') NOT NULL,
    detalles TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla del carrito
CREATE TABLE carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos de ejemplo con acentos
-- --------------------------------------------------------
INSERT INTO categorias (nombre, descripcion, icono) VALUES
('Bebidas Calientes', 'Cafés, tés y chocolate', 'coffee'),
('Bebidas Frías', 'Frappés, jugos, smoothies', 'cold'),
('Postres', 'Pasteles, galletas, brownies', 'cake'),
('Salados', 'Sándwiches, quiches', 'sandwich');

INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen, alergenos, destacado) VALUES
(1, 'Caviar Latte', 'Espresso con leche, suave y cremoso', 5.00, 'caviar_latte.jpg', 'lácteos', TRUE),
(1, 'Chai Espresso', 'Café con especias aromáticas', 6.00, 'chai_espresso.jpg', NULL, TRUE),
(1, 'Mocha Espresso', 'Chocolate y café', 7.00, 'mocha.jpg', 'lácteos', TRUE),
(2, 'Frappé de Vainilla', 'Bebida fría con hielo y crema', 6.50, 'frappe_vainilla.jpg', 'lácteos', FALSE),
(3, 'Cheesecake', 'Pastel de queso con frutos rojos', 4.50, 'cheesecake.jpg', 'lácteos,gluten', TRUE);
