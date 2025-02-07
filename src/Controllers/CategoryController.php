<?php 
namespace Controllers;

use Services\CategoryService;
use Repositories\CategoryRepository;
use Lib\Pages;
use Lib\Database;
use PDO;
use PDOException;

class CategoryController
{
    private $categoryService;
    private $pages;
    private $carrito = [];

    public function __construct()
    {
        // Asegúrate de reemplazar 'tu_usuario', 'tu_contraseña' y 'tu_base_de_datos' con tus credenciales reales
        $dsn = 'mysql:host=localhost;dbname=tienda;charset=utf8';
        $username = 'root';
        $password = '';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $db = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

        $categoryRepository = new CategoryRepository($db);
        $this->categoryService = new CategoryService($categoryRepository);
        $this->pages = new Pages();
    }

    public function ver_Form()
    {
        error_log("Checkpoint: Entrando al método ver_Form");
        $this->pages->render('Auth/categotyform');
    }

    public function guardarCategoria()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            if ($this->categoryService->addCategory($nombre)) {
                echo "Categoría insertada correctamente.";
                header('Location: ' . BASE_URL);
                exit;
            } else {
                echo "Error al insertar la categoría.";
            }
        } else {
            $this->ver_Form();
        }
    }

    public function verCategorias()
    {
        error_log("Checkpoint: Entrando al método verCategorias");
        $categorias = $this->categoryService->getAllCategories();
        $this->pages->render('Auth/verCategorias', ['categorias' => $categorias]);
    }

    public function añadirProducto()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $producto = $_POST['producto'] ?? '';
            if (!empty($producto)) {
                $this->carrito[] = $producto;
                echo "Producto añadido al carrito.";
            } else {
                echo "Error: No se ha especificado un producto.";
            }
        }
    }
}
