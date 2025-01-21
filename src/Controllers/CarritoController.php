<?php
namespace Controllers;
use Models\Producto;
use PDO;
use PDOException;
class CarritoController
{
    private $model;

    public function __construct($pdo) {
        $this->model = new Producto($pdo);
    }

    // Agregar producto al carrito
    public function addToCart($productName, $productPrice) {
        // Inicializar carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Producto a agregar
        $producto = [
            'nombre' => $productName,
            'precio' => $productPrice,
            'cantidad' => 1
        ];

        // Verificar si el producto ya está en el carrito
        $productoExistente = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['nombre'] === $producto['nombre']) {
                $item['cantidad'] += 1; // Incrementar cantidad
                $productoExistente = true;
                break;
            }
        }

        // Si no está en el carrito, agregarlo
        if (!$productoExistente) {
            $_SESSION['carrito'][] = $producto;
        }
    }

    // Obtener todas las categorías
    public function getCategories() {
        return $this->model->getCategories();
    }

    // Obtener los productos por categoría
    public function getProducts($categoryId) {
        return $this->model->getProductsByCategory($categoryId);
    }

    // Mostrar carrito
    public function showCart() {
        return $_SESSION['carrito'];
    }
}
