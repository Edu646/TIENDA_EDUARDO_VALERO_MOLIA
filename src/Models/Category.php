<?php

namespace Models;
use PDO;
use Models\Producto;
use PDOException;
class Category
{
    private $id;
    private $nombre;

    public function __construct($nombre, $id = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getProductos() {
        try {
            // Conexión a la base de datos
            $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Consulta para obtener los productos por categoría
            $stmt = $db->prepare('SELECT * FROM productos WHERE categoria_id = :categoria_id');
            $stmt->bindParam(':categoria_id', $this->id, \PDO::PARAM_INT);
            $stmt->execute();

            // Fetch de los resultados
            $productos = $stmt->fetchAll(\PDO::FETCH_CLASS, 'Models\Producto');

            return $productos;
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }
}