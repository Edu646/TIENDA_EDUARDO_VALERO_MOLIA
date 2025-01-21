<?php
namespace Repositories;

use Models\Producto;
use PDO;

class ProductoRepository{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insert(Producto $producto){
        $stmt = $this->db->prepare("INSERT INTO Productos (nombre, categoria_id, descripcion, precio, stock, oferta, fecha, imagen) VALUES (:nombre, :categoria_id, :descripcion, :precio, :stock, :oferta, :fecha, :imagen)");
        $stmt->bindParam(':nombre', $producto->getNombre());
        $stmt->bindParam(':categoria_id', $producto->getCategoriaId());
        $stmt->bindParam(':descripcion', $producto->getDescripcion());
        $stmt->bindParam(':precio', $producto->getPrecio());
        $stmt->bindParam(':stock', $producto->getStock());
        $stmt->bindParam(':oferta', $producto->getOferta());
        $stmt->bindParam(':fecha', $producto->getFecha());
        $stmt->bindParam(':imagen', $producto->getImagen());
        return $stmt->execute();
    }

    public function findAll(){
        $stmt = $this->db->query("SELECT * FROM Productos");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($data) {
            return Producto::fromArray($data);
        }, $result);
    }
}