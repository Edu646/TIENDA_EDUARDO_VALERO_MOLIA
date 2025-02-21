<?php

namespace Routes;
use Controllers\CategoryController;
use Controllers\AuthController;
use Controllers\PaymentController;
use Controllers\ProductoController;
use Lib\Router;  
use Src\Controllers\ErrorController;
use Lib\Database;
use Controllers\PedidoController;

class Routes {
    public static function index() {
        error_log("Checkpoint: Entrando a Routes::index");

        Router::add('GET', '/', function () {
            error_log("Checkpoint: Cargando la vista de inicio");
            // Usa tu clase Pages para cargar la vista de inicio
            $pages = new \Lib\Pages();
            $pages->render('inicio'); 
        });

        



        Router::add('GET', '/listus/', function () {
            if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
                error_log("Acceso denegado a admin, redirigiendo a /error/");
                return (new ErrorController())->error404();
            }
        
        });
        
        
        // Ruta para errores
        Router::add('GET', '/error/', function () {
            error_log("Checkpoint: Ruta de error ejecutada");
            return (new ErrorController())->error404();
        });
        
        /* AUTH */
        Router::add('GET', '/register', function () {
            error_log("Checkpoint: Ruta GET /register ejecutada");
            (new AuthController())->register();
        });

        Router::add('POST', 'register', function () {
            error_log("Checkpoint: Ruta POST /register ejecutada");
            (new AuthController())->register();
        });

        // login
        Router::add('GET', 'login', function () {
            error_log("Checkpoint: Ruta GET /login ejecutada");
            (new AuthController())->login();
        });

        Router::add('POST', 'login', function () {
            error_log("Checkpoint: Ruta POST /login ejecutada");
            (new AuthController())->processLogin();
        });

        Router::add('GET', '/listus', function () {
            (new AuthController())->listUsers();
        });

        Router::add('GET', '/edit/{id}', function ($id) {
            (new AuthController())->editUser($id);
        });

        Router::add('POST', '/edit', function ($id) {
            (new AuthController())->editUser($id);
        });
        
        Router::add('POST', '/AddUs', function () {
            (new AuthController())->addUser();
        });

        Router::add('POST', '/DelUs', function ($email) {
            (new AuthController())->deleteUser($email);
        });
       
        // logout
        Router::add('GET', 'logout', function () {
            error_log("Checkpoint: Ruta GET /logout ejecutada");
            session_destroy(); 
            header('Location: ' . BASE_URL); 
            exit;
        });

        // test-db
        Router::add('GET', 'test-db', function () {
            error_log("Checkpoint: Ruta GET /test-db ejecutada");
            try {
                $db = Database::getConnection();
                echo "Conexión exitosa a la base de datos.";
            } catch (\Exception $e) {
                error_log("Error: " . $e->getMessage());
                echo "Error al conectar a la base de datos: " . $e->getMessage();
            }
        });

        /* CATEGORY CONTROLLER */
        Router::add('GET', '/vista', function () {
            (new CategoryController())->ver_Form();
        });

        Router::add('POST', 'vista', function () {
            (new CategoryController())->guardarCategoria();
        });

        Router::add('GET', '/ver', function () {
            (new CategoryController())->verCategorias();
        });
        
        Router::add('GET', '/CrearP', function () {
            (new ProductoController())->ver_Form();
        });

        Router::add('POST', 'CrearP', function () {
            (new ProductoController())->addProducto();
        });

        Router::add('GET', '/verP', function () {
            (new ProductoController())->verProductos();
        });

        Router::add('GET', '/verP_admin', function () {
            (new ProductoController())->verProductos_admin();
        });

        Router::add('POST', '/verP_admin', function () {
            (new ProductoController())->eliminarProducto();
        });

        Router::add('POST', '/addcart', function () {
            (new ProductoController())->agregarAlCarrito();
        });

        Router::add('POST', '/eliminarcartus', function () {
            (new ProductoController())->eliminarDelCarrito();
        });


        Router::add('POST', '/editarCategoria', function () {
            (new CategoryController())->editarCategoria();
        });

        Router::add('POST', '/borrarCategoria', function () {
            (new CategoryController())->borrarCategoria();
        });
        
        Router::add('GET', '/adminCategorias', function () {
            (new CategoryController())->adminCategorias();
        });

        Router::add('POST', '/disminuirCantidad', function () {
            (new ProductoController())->disminuirCantidad();
        });


        Router::add('POST', '/eliminarDelCarrito', function () {
            (new ProductoController())->eliminarDelCarrito();
        });


        Router::add('POST', '/aumentarCantidad', function () {
            (new ProductoController())->aumentarCantidad();
        });

        Router::add('GET', '/carrito', function () {
            error_log("Checkpoint: Cargando la vista de inicio");
            // Usa tu clase Pages para cargar la vista de inicio
            $pages = new \Lib\Pages();
            $pages->render('Carrito'); 
        });

    

    Router::add('POST', '/crearPedido', function () {
    (new PedidoController())->crearPedido();
    });

    Router::add('GET', '/confirmRegistration', function () {
        (new AuthController())->confirmRegistration();
    });


    Router::add('GET', '/confirmacion', function () {
        (new PedidoController())->confirmacion();
    });

    Router::add('POST', '/sendPasswordRecoveryToken', function () {
        (new AuthController())->sendPasswordRecoveryToken();
    });

    Router::add('GET', '/resetPassword', function () {
        (new AuthController())->resetPassword();
    });

    Router::add('POST', '/resetPassword', function () {
        (new AuthController())->resetPassword();
    });


    Router::add('GET', '/mis-pedidos', function () {
        (new PedidoController())->verPedidos();
    });
    
    Router::add('GET', '/ver-detalle', function ($id) {
        (new PedidoController())->gestionarPedidos($id);
    });
    
    // Rutas para administradores
    Router::add('GET', '/pedidos', function () {
        (new PedidoController())->actualizarEstadoPedido();
    });
    
    Router::add('POST', '/actualizar-estado', function () {
        (new PedidoController())->actualizarEstadoPedido();
    });

// Obtener todos los productos
Router::add('GET', '/api/productos', function () {
    (new ProductoController())->getAllProductosapi();
});

// Obtener un producto por ID
Router::add('GET', '/api/productos/:id', function ($id) {
    (new ProductoController())->getById($id);
});

// Crear un nuevo producto
Router::add('POST', '/api/producto', function () {
    (new ProductoController())->addProductoApi();
});

// Actualizar un producto por ID
Router::add('PUT', '/api/productos/:id', function ($id) {
    (new ProductoController())->updateProducto($id);
});

// Eliminar un producto por ID

Router::add('DELETE', '/api/productos/:id', function($id) {
    return (new ProductoController())->delete($id);
}); 

Router::add('POST', '/checkout', function() {
    return (new PaymentController())->checkout();
});

Router::add('GET', '/payment/success', function() {
    return (new PaymentController())->success();
});

Router::add('GET', '/payment/cancel', function() {
    return (new PaymentController())->cancel();
});

Router::add('GET', '/payment/completed', function() {
    return (new PaymentController())->completed();
});


// ...existing code...


        Router::dispatch();
    }
}
