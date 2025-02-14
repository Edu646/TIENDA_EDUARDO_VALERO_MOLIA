<?php

namespace Routes;
use Controllers\CategoryController;
use Controllers\AuthController;
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

        Router::add('POST', '/actualizarCantidadCarrito', function () {
            (new ProductoController())->actualizarCantidadCarrito();
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

    Router::add('POST', '/sendRecoveryEmail', function () {
        (new AuthController())->sendRecoveryEmail();
    });

    Router::add('POST', '/resetPassword', function () {
        (new AuthController())->resetPassword();
    });

    Router::add('GET', '/resetPassword', function () {
        (new AuthController())->resetPassword();
    });


    Router::add('GET', '/confirmacion', function () {
        (new PedidoController())->confirmacion();
    });



    Router::add('POST', '/showRecoveryForm', function () {
        (new AuthController())->showRecoveryForm();
    });
// ...existing code...


        Router::dispatch();
    }
}
