<?php

namespace Routes;

use Controllers\CategoryController;
use Controllers\AuthController;
use Controllers\UserController; 
use Controllers\AdminController;
use Controllers\ProductoController;
use Lib\Router;  
use Src\Controllers\ErrorController;
use Lib\Database;

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
        
        Router::add('GET', '/carrito', function () {
            error_log("Checkpoint: Cargando la vista de inicio");
            // Usa tu clase Pages para cargar la vista de inicio
            $pages = new \Lib\Pages();
            $pages->render('Carrito'); 
        });
        


        // Despachar las rutas
        error_log("Checkpoint: Despachando rutas");
        Router::dispatch();
    }
}