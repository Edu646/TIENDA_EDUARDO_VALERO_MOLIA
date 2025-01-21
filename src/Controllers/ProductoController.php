<?php 

namespace Controllers;

use Services\ProductoService;
use Lib\Pages;
use Lib\Database;
use PDO;
use PDOException;

class ProductoController {
    private $productoService;
    private $pages;

    public function __construct() {
        $db = new Database();
        $pdo = $db->getConnection();
        $productoRepository = new \Repositories\ProductoRepository($pdo);
        $this->productoService = new ProductoService($productoRepository);
        $this->pages = new Pages();
    }

    public function ver_Form()
    {
        error_log("Checkpoint: Entrando al método ver_Form");
        $this->pages->render('Product/Productoform');
    }

    public function addProducto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? null;
            $categoria_id = $_POST['categoria_id'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $precio = $_POST['precio'] ?? null;
            $stock = $_POST['stock'] ?? null;
            $oferta = $_POST['oferta'] ?? null;
            $fecha = $_POST['fecha'] ?? null;
            $imagen = null;

            // Manejar la subida de la imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $uploadFile = $uploadDir . basename($_FILES['imagen']['name']);
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
                    $imagen = $uploadFile;
                } else {
                    echo 'Error al subir la imagen';
                    return;
                }
            } else {
                $imagen = $_POST['imagen_url'] ?? null;
            }

            if ($nombre) {
                $producto = new \Models\Producto($nombre, null, $categoria_id, $descripcion, $precio, $stock, $oferta, $fecha, $imagen);
                $result = $this->productoService->addProducto($producto);

                if ($result) {
                    echo 'Producto añadido con éxito';
                } else {
                    echo 'Error al añadir el producto';
                }
            } else {
                echo 'Nombre del producto es requerido';
            }
        } else {
            $this->pages->render('Product/Productoform');
        }
    }

    public function verProductos() {
        $productos = $this->productoService->getAllProductos();
        $this->pages->render('Product/VerProductos', ['productos' => $productos]);
    }
}