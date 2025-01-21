<?php
require_once __DIR__ . '/../config/config.php';

// Inicia sesión para manejar variables de sesión
session_start();

use Dotenv\Dotenv;
use Routes\Routes;

// Cargar el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar la configuración del archivo .env usando safeLoad
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Forzar las variables del .env a sobrescribir las globales
$_ENV['SERVERNAME'] = $_ENV['SERVERNAME'] ?? 'localhost';
$_ENV['USERNAME'] = $_ENV['USERNAME'] ?? 'root';
$_ENV['PASSWORD'] = $_ENV['PASSWORD'] ?? '';
$_ENV['DATABASE'] = $_ENV['DATABASE'] ?? 'tienda';
$_ENV['DB_CHARSET'] = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

foreach ($_ENV as $key => $value) {
    $_SERVER[$key] = $value;
}

 //Verificar si las variables del entorno están cargadas correctamente
 if (empty($_ENV['SERVERNAME']) || empty($_ENV['USERNAME']) || empty($_ENV['DATABASE']) || empty($_ENV['DB_CHARSET'])) {
    throw new Exception('Error: No se pudieron cargar las variables de entorno. Verifica el archivo .env.');

}

// Depuración de variables (opcional, puedes activarlo para verificar el contenido)
// if (isset($_GET['debug']) && $_GET['debug'] === 'true') {
    //echo '<pre>';
   // echo "Contenido de \$_ENV:\n";
    //print_r($_ENV);
    //echo "\nContenido de \$_SERVER:\n";
    //print_r($_SERVER);
   // echo "\nContenido de getenv():\n";
   // print_r(getenv());
    //echo '</pre>';
    //exit;
//}

// Llama al enrutador principal para iniciar la aplicación
Routes::index();

