<?php

namespace Repositories;

use Models\Producto;
use PDO;

class ProductoRepository {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function insert(Producto $producto) {
        try {
            $stmt = $this->db->prepare("INSERT INTO Productos (nombre, categoria_id, descripcion, precio, stock, oferta, fecha, imagen) 
                                        VALUES (:nombre, :categoria_id, :descripcion, :precio, :stock, :oferta, :fecha, :imagen)");

            // Asignamos los valores a variables antes de pasarlos a bindParam()
            $nombre = $producto->getNombre();
            $categoria_id = $producto->getCategoriaId();
            $descripcion = $producto->getDescripcion();
            $precio = $producto->getPrecio();
            $stock = $producto->getStock();
            $oferta = $producto->getOferta();
            $fecha = $producto->getFecha();
            $imagen = $producto->getImagen();

            // Usamos bindParam con las variables en lugar de los métodos directamente
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':oferta', $oferta, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':imagen', $imagen);

            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log('Error al insertar el producto: ' . $e->getMessage());
            return false;
        }
    }

    public function findAll() {
        $stmt = $this->db->query("SELECT * FROM Productos");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($data) {
            return Producto::fromArray($data);
        }, $result);
    }

    public function deleteProductoById($producto_id) {
        try {
            $sql = "DELETE FROM Productos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $producto_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log('Error al eliminar el producto: ' . $e->getMessage());
            return false;
        }
    }
   

    public function findById($producto_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Productos WHERE id = :id");
            $stmt->bindParam(':id', $producto_id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($data) {
                return Producto::fromArray($data);
            }
            return null;
        } catch (\PDOException $e) {
            error_log('Error al obtener producto: ' . $e->getMessage());
            return null;
        }
    }

    public function getConnection() {
        // Assuming you have a database connection setup
        // Replace the following line with your actual database connection code
        return new \PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    }

    public function reducirStock(int $productoId, int $cantidad): bool
    {
        $stmt = $this->db->prepare("UPDATE productos SET stock = stock - :cantidad WHERE id = :producto_id");
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':producto_id', $productoId);
        return $stmt->execute();
    }
}
    
    


