<?php
namespace src\Controllers;


    class ErrorController
{
    public static function error404(string $message = 'Página no encontrada'): void
    {
        echo "<h1>Error 404</h1>";
        echo "<p>$message</p>";
    }
}