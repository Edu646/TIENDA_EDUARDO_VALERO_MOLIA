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
            $nombre = $_POST['nombre'] ?? '';
            $categoria_id = $_POST['categoria_id'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? '';
            $stock = $_POST['stock'] ?? '';
            $oferta = $_POST['oferta'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $imagen = '';

            // Manejar la subida de la imagen
            if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                
                // Verificar y crear la carpeta si no existe
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $uploadFile = $uploadDir . basename($_FILES['imagen']['name']);
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
                    $imagen = $uploadFile;
                } else {
                    error_log('Error al mover el archivo: ' . $_FILES['imagen']['error']);
                    header('Location: /error');
                    exit;
                }
            } else {
                $imagen = $_POST['imagen_url'] ?? '';
            }

            if (!empty($nombre)) {
                $producto = new \Models\Producto($nombre, null, $categoria_id, $descripcion, $precio, $stock, $oferta, $fecha, $imagen);
                $result = $this->productoService->addProducto($producto);

                if ($result) {
                    header('Location: ' . BASE_URL );
                    exit;
                } else {
                    header('Location: /error');
                    exit;
                }
            } else {
                header('Location: /error');
                exit;
            }
        } else {
            $this->pages->render('Product/Productoform');
        }
    }

    public function verProductos() {
        $productos = $this->productoService->getAllProductos();
        $this->pages->render('Product/VerProductos', ['productos' => $productos]);
    }

    public function verProductos_admin() {
        $productos = $this->productoService->getAllProductos();
        $this->pages->render('Product/VerProductos_admin', ['productos' => $productos]);
    }

    public function eliminarProducto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
            $producto_id = $_POST['producto_id'];
            
            $result = $this->productoService->deleteProducto($producto_id);
            
            if ($result) {
                header('Location: ' . BASE_URL );
                exit;
            } else {
                header('Location: /error');
                exit;
            }
        }
    }
}
