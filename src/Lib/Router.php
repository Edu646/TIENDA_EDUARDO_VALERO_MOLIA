<?php
namespace Lib;

class Router {

    private static $routes = [];

    // Método para añadir rutas con su controlador correspondiente
    public static function add(string $method, string $action, Callable $controller): void {
        $action = trim($action, '/'); // Elimina barras iniciales y finales de la acción
        self::$routes[$method][$action] = $controller;
    }

    // Método para despachar la ruta solicitada
    public static function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD']; // Obtener el método de la solicitud (GET, POST, DELETE, etc.)
        $action = preg_replace('/TiendaEduardo/', '', $_SERVER['REQUEST_URI']); // Remover el prefijo "miAgenda" de la URL
        $action = trim($action, '/'); // Eliminar barras iniciales y finales

        $param = null; // Parámetro opcional en la URL
        preg_match('/[0-9]+$/', $action, $match); // Buscar números al final de la URL

        if (!empty($match)) {
            $param = $match[0]; // Guardar el parámetro encontrado
            $action = preg_replace('/' . $match[0] . '/', ':id', $action); // Reemplazar el número por ':id'
        }

        // Depuración: Mostrar rutas y la acción solicitada
        // echo "Debug: Método = $method, Acción = $action<br>";

        // Comprobar si la ruta existe en las rutas definidas
        $fn = self::$routes[$method][$action] ?? null; // Obtener la función asociada a la ruta

        if ($fn) {
            // Ejecutar el callback de la ruta correspondiente
            // echo "Debug: Ruta encontrada. Ejecutando callback.<br>";
            echo call_user_func($fn, $param); // Llamar a la función de la ruta
        } else {
            // echo "Debug: Ruta no encontrada. Llamando a ErrorController::error404().<br>";
            // Podrías implementar un controlador para manejar rutas no encontradas
        }
    }
}
