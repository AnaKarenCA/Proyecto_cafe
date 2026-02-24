USE cafeteria_escolar;

CREATE TABLE categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id)
);

INSERT INTO categoria(nombre) VALUES
('Bebidas'),
('Snacks'),
('Comida');

INSERT INTO producto(nombre,precio,id_categoria) VALUES
('Café Americano',20.00,1),
('Capuccino',30.00,1),
('Galletas',15.00,2),
('Sandwich',35.00,3);