<?php 

namespace Controllers;

use Services\ProductoService;
use Lib\Pages;
use Lib\Database;
use Models\Producto;
use PDO;
use PDOException;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

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
        $errors = [];
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
                    echo "Error al subir la imagen.<br>";
                }
            } else {
                $imagen = $_POST['imagen_url'] ?? '';
            }
    
            if (!empty($nombre) && !empty($categoria_id)) {
                try {
                    $producto = new Producto($nombre, null, $categoria_id, $descripcion, $precio, $stock, $oferta, $fecha, $imagen);
                    $result = $this->productoService->addProducto($producto);
                    if ($result) {
                        header('Location: ' . BASE_URL);
                        exit;
                    }
                } catch (\InvalidArgumentException $e) {
                    // Capturar el error y pasarlo al formulario
                    $errors['precio'] = $e->getMessage();
                }
            } else {
                $errors['general'] = "Error: Nombre y categoría son obligatorios.";
            }
        }
        $this->pages->render('Product/Productoform', ['errors' => $errors]);
    }

    public function verProductos() {
        $productos = $this->productoService->getAllProductos();
        $this->pages->render('Product/VerProductos', ['productos' => $productos]);
       
    }

    public function verProductosini() {
        $productos = $this->productoService->getAllProductos();
        $this->pages->render('inicio', ['productos' => $productos]);
       
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
    
                $productoExistente = false;
    
                // Recorremos el carrito para ver si el producto ya está agregado
                foreach ($_SESSION['carrito'] as &$item) {
                    if ($item['producto_id'] == $producto_id) {
                        // Si el producto ya está en el carrito, aumentamos la cantidad
                        $nuevaCantidad = $item['cantidad'] + $cantidad;
    
                        // Asegurar que la cantidad no exceda el stock
                        if ($nuevaCantidad > $producto->getStock()) {
                            $nuevaCantidad = $producto->getStock();
                            $_SESSION['error'] = "No puedes agregar más de " . $producto->getStock() . " unidades de este producto.";
                        }
    
                        // Actualizar la cantidad y el precio total del producto en el carrito
                        $item['cantidad'] = $nuevaCantidad;
                        $item['precio_total'] = $producto->getPrecio() * $nuevaCantidad;
                        $productoExistente = true;
                        break;
                    }
                }
    
                // Si el producto no existe en el carrito, agregarlo como nuevo
                if (!$productoExistente) {
                    $_SESSION['carrito'][] = [
                        'producto_id' => $producto_id,
                        'nombre' => $producto->getNombre(),
                        'precio' => $producto->getPrecio(),
                        'precio_total' => $producto->getPrecio() * $cantidad,
                        'cantidad' => $cantidad,
                        'imagen' => $producto->getImagen(),
                        'stock' => $producto->getStock(),
                    ];
                }
    
                // Calcular el total del carrito
                $_SESSION['total_carrito'] = array_sum(array_column($_SESSION['carrito'], 'precio_total'));
    
                // Redirigir al carrito
                header('Location: ' . BASE_URL . '/carrito');
                exit;
            }
        }
    
        // Si el producto no existe o no se pasa el producto_id, redirigir a error
        header('Location: /error');
        exit;
    }
    

    
    
    public function actualizarCantidadCarrito() {
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cantidad'])) {
            // Verificar si el carrito está en la sesión
            if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
                foreach ($_POST['cantidad'] as $producto_id => $cantidad) {
                    // Buscar el producto en el carrito
                    foreach ($_SESSION['carrito'] as &$item) {
                        if ($item['producto_id'] == $producto_id) {
                            // Obtener los detalles del producto desde la base de datos
                            $producto = $this->productoService->getProductoById($producto_id);
    
                            if ($producto) {
                                // Asegurar que la cantidad no supere el stock disponible
                                if ($cantidad > $producto->getStock()) {
                                    $cantidad = $producto->getStock();
                                    $_SESSION['error'] = "No puedes agregar más de " . $producto->getStock() . " unidades de este producto.";
                                }
    
                                // Actualizar la cantidad y el precio total del producto en el carrito
                                $item['cantidad'] = $cantidad;
                                $item['precio_total'] = $producto->getPrecio() * $cantidad;
                            }
                        }
                    }
                }
    
                // Calcular el total del carrito
                $_SESSION['total_carrito'] = array_sum(array_column($_SESSION['carrito'], 'precio_total'));
    
                // Redirigir al carrito
                header('Location: ' . BASE_URL . '/carrito');
                exit;
            }
        }
    
        // Si no se encuentra el producto en el carrito o no se pasa el producto_id, redirigir a error
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


    public function aumentarCantidad() {
        session_start();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
            $producto_id = $_POST['producto_id'];
    
            // Verificar si el carrito está en la sesión
            if (isset($_SESSION['carrito'])) {
                // Recorrer el carrito y buscar el producto
                foreach ($_SESSION['carrito'] as &$item) {
                    if ($item['producto_id'] == $producto_id) {
                        // Obtener los detalles del producto desde la base de datos
                        $producto = $this->productoService->getProductoById($producto_id);
    
                        if ($producto) {
                            // Asegurar que la cantidad no supere el stock disponible
                            if ($item['cantidad'] < $producto->getStock()) {
                                $item['cantidad']++;
                                $item['precio_total'] = $producto->getPrecio() * $item['cantidad'];
                            } else {
                                $_SESSION['error'] = "No puedes agregar más de " . $producto->getStock() . " unidades de este producto.";
                            }
                        }
                        break;
                    }
                }
    
                // Calcular el total del carrito
                $_SESSION['total_carrito'] = array_sum(array_column($_SESSION['carrito'], 'precio_total'));
    
                // Redirigir al carrito
                header('Location: ' . BASE_URL . '/carrito');
                exit;
            }
        }
    
        // Si no se encuentra el producto en el carrito o no se pasa el producto_id, redirigir a error
        header('Location: /error');
        exit;
    }
    
    public function disminuirCantidad() {
        session_start();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
            $producto_id = $_POST['producto_id'];
    
            // Verificar si el carrito está en la sesión
            if (isset($_SESSION['carrito'])) {
                // Recorrer el carrito y buscar el producto
                foreach ($_SESSION['carrito'] as &$item) {
                    if ($item['producto_id'] == $producto_id) {
                        // Disminuir la cantidad del producto en el carrito
                        if ($item['cantidad'] > 1) {
                            $item['cantidad']--;
                            $item['precio_total'] = $item['precio'] * $item['cantidad'];
                        } else {
                            $_SESSION['error'] = "No puedes tener menos de 1 unidad de este producto.";
                        }
                        break;
                    }
                }
    
                // Calcular el total del carrito
                $_SESSION['total_carrito'] = array_sum(array_column($_SESSION['carrito'], 'precio_total'));
    
                // Redirigir al carrito
                header('Location: ' . BASE_URL . '/carrito');
                exit;
            }
        }
    
        // Si no se encuentra el producto en el carrito o no se pasa el producto_id, redirigir a error
        header('Location: /error');
        exit;
    }

    public function getAllProductosapi() {
        $productos = $this->productoService->getAllProductosapi();
        echo json_encode($productos);
    }
    

    public function getById($id) {
        $producto = $this->productoService->getById($id);
        echo json_encode($producto);
    }

    public function updateProducto($id) {
        $input = json_decode(file_get_contents("php://input"), true);
    
        if (!empty($input)) {
            try {
                $producto = new Producto(
                    $input['nombre'], 
                    $id, 
                    $input['categoria_id'], 
                    $input['descripcion'], 
                    $input['precio'], 
                    $input['stock'], 
                    $input['oferta'], 
                    $input['fecha'], 
                    $input['imagen']
                );
                $result = $this->productoService->updateProducto($producto);
                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'No se proporcionaron datos para actualizar']);
        }
    }

    public function addProductoApi() {
        // Obtener los datos del cuerpo de la solicitud
        $input = json_decode(file_get_contents("php://input"), true);
    
        if (!empty($input)) {
            try {
                // Validación básica de los datos requeridos
                if (empty($input['nombre']) || empty($input['categoria_id']) || empty($input['precio']) || empty($input['stock'])) {
                    echo json_encode(['error' => 'Los campos nombre, categoria_id, precio y stock son obligatorios']);
                    return;
                }
    
                // Crear una nueva instancia del modelo Producto
                $producto = new Producto(
                    $input['nombre'], 
                    null,  // ID se autogenera
                    $input['categoria_id'], 
                    $input['descripcion'] ?? '', 
                    $input['precio'], 
                    $input['stock'], 
                    $input['oferta'] ?? 0, 
                    $input['fecha'] ?? date('Y-m-d'), 
                    $input['imagen'] ?? ''
                );
    
                // Intentar agregar el producto
                $result = $this->productoService->addProducto($producto);
                
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Producto agregado correctamente']);
                } else {
                    echo json_encode(['error' => 'No se pudo agregar el producto']);
                }
            } catch (\Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'No se enviaron datos']);
        }
    }

// Método para borrar un producto a través de la API
public function delete($id) {
    $result = $this->productoService->delete($id);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
    }
}

    
    
    
}