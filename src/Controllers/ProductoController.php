<?php 

namespace Controllers;

use Services\ProductoService;
use Lib\Pages;
use Lib\Database;
use Models\Producto;
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

    public function ver_Form() {
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

            if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
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

            if (!empty($nombre) && !empty($categoria_id)) {
                $producto = new Producto($nombre, null, $categoria_id, $descripcion, $precio, $stock, $oferta, $fecha, $imagen);
                $result = $this->productoService->addProducto($producto);
                if ($result) {
                    header('Location: ' . BASE_URL );
                    exit;
                }
            }
            header('Location: /error');
            exit;
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
            }
        }
        header('Location: /error');
        exit;
    }

    public function agregarAlCarrito() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
            $producto_id = $_POST['producto_id'];
            $cantidad = $_POST['cantidad'] ?? 1;  // Obtener la cantidad desde el formulario (por defecto 1)
    
            // Obtener los detalles del producto a partir de la base de datos
            $producto = $this->productoService->getProductoById($producto_id);
    
            if ($producto) {
                // Verificar si la cantidad solicitada no excede el stock disponible
                if ($cantidad > $producto->getStock()) {
                    // Si la cantidad es mayor que el stock, establecer la cantidad máxima disponible
                    $cantidad = $producto->getStock();
                    $_SESSION['error'] = "No hay suficiente stock disponible. Se agregará la cantidad máxima disponible: " . $cantidad . " unidades.";
                }
    
                // Si la sesión del carrito no está iniciada, iniciarla
                if (!isset($_SESSION['carrito'])) {
                    $_SESSION['carrito'] = [];
                }
    
                // Comprobar si el producto ya está en el carrito
                $productoExistente = false;
                foreach ($_SESSION['carrito'] as &$item) {
                    if ($item['producto_id'] == $producto_id) {
                        // Si el producto ya existe en el carrito, actualizamos la cantidad
                        $nuevaCantidad = $item['cantidad'] + $cantidad;
    
                        // Asegurarse de que la cantidad no exceda el stock
                        if ($nuevaCantidad > $producto->getStock()) {
                            $nuevaCantidad = $producto->getStock();
                            $_SESSION['error'] = "No puedes agregar más de " . $producto->getStock() . " unidades de este producto. Se ajustará la cantidad.";
                        }
                        $item['cantidad'] = $nuevaCantidad;
                        $productoExistente = true;
                        break;
                    }
                }
    
                // Si el producto no está en el carrito, agregarlo con la cantidad especificada
                if (!$productoExistente) {
                    $_SESSION['carrito'][] = [
                        'producto_id' => $producto_id,
                        'nombre' => $producto->getNombre(),
                        'precio' => $producto->getPrecio(),
                        'cantidad' => $cantidad,
                        'imagen' => $producto->getImagen(),
                        'stock' => $producto->getStock(),
                    ];
                }
    
                // Redirigir al carrito
                header('Location: ' . BASE_URL . '/carrito');
                exit;
            }
        }
    
        // Si el producto no existe o no se pasa el producto_id, redirigir a error
        header('Location: /error');
        exit;
    }
    

    

    
    

    public function eliminarDelCarrito() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
            $producto_id = $_POST['producto_id'];
    
            // Verificar si el carrito está en la sesión
            if (isset($_SESSION['carrito'])) {
                // Recorrer el carrito y buscar el producto
                foreach ($_SESSION['carrito'] as $index => $item) {
                    if ($item['producto_id'] == $producto_id) {
                        // Eliminar el producto del carrito
                        unset($_SESSION['carrito'][$index]);
    
                        // Reindexar el array del carrito para evitar huecos en los índices
                        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    
                        // Redirigir al carrito o a la página correspondiente
                        header('Location: ' . BASE_URL . '/carrito');
                        exit;
                    }
                }
            }
        }
        // Si no se encuentra el producto en el carrito o no se pasa el producto_id, redirigir a error
        header('Location: /error');
        exit;
    }

    
    
    
    
}
