//Estructura del proyecto
Proyecto_cafe/
│
├── app/                         # Lógica principal de la aplicación (MVC)
│
│   ├── controllers/             # Controladores: gestionan la lógica entre modelo y vista
│   │   ├── AuthController.php
│   │   ├── AdminCategoriaController.php
│   │   ├── AdminPedidoController.php
│   │   ├── AdminReservaController.php
│   │   ├── CategoriaController.php
│   │   ├── PedidoController.php
│   │   ├── ProductoController.php
│   │   ├── CarritoController.php
│   │   ├── IdiomaController.php
│   │   ├── ReservaController.php
│   │   ├── UsuarioController.php
│   │   └── WelcomeController.php
│   │
│   ├── models/                  # Modelos: representan las tablas de la base de datos
│   │   ├── Usuario.php
│   │   ├── Categoria.php
│   │   ├── Producto.php
│   │   ├── Carrito.php
│   │   └── Reserva.php
│   │   └── DetallePedido.php
│   │   └── Pedido.php
│   │
│   └── views/                   # Vistas: interfaz que ve el usuario
│       │
│       ├── layout/              # Plantillas reutilizables del sitio
│       │   ├── header.php
│       │   ├── auth_header.php
│       │   ├── footer.php
│       │   ├── auth_footer.php
│       │   └── menu.php
│       │   └── carrito.php
│       │
│       ├── auth/                # Interfaces de autenticación
│       │   ├── login.php
│       │   └── registro.php
│       │
│       ├── usuario/             # Gestión del perfil del usuario
│       │   └── perfil.php
│       │   └── pedidos.php
│       │   └── reservas.php
│       │
│       ├── welcome.php          # Página de inicio del sitio
│       ├── categorias.php       # Vista de categorías de productos
│       └── categoria.php 
│       └── categorias.php 
│       └── checkout.php 
│       ├── productos.php        # Catálogo de productos
│       ├── producto_detalle.php
│       ├── reserva.php          # Formulario de reservas
│       └── carrito.php          # Panel lateral del carrito de compras
│       └── carrito_completo.php 
│
├── config/                      # Configuración del sistema
│   ├── database.php             # Conexión a base de datos usando PDO
│   └── routes.php               # Definición de rutas (controlador/acción)
│
├── docker/                      # Entorno de contenedores
│   ├── docker-compose.yml       # Definición de servicios (PHP, MySQL)
│   ├── init/
│      └── 01_init.sql                 # Script de creación de base de datos
│
├── public/                      # Punto público del servidor
│   │
│   ├── index.php                # Front Controller (entrada principal)
│   │
│   ├── css/                     # Archivos de estilo
│   │   └── style.css
│   │
│   ├── js/                      # Scripts JavaScript
│   │   └── main.js
│   │
│   └── img/                     # Imágenes de productos
│       ├── caviar_latte.jpg
