<?php

namespace Repositories;

use Models\Category;
use PDO;

class CategoryRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insert(Category $category)
    {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $category->getNombre());
        return $stmt->execute();
    }

    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM categorias");
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category($row['nombre'], $row['id']);
        }
        return $categories;
    }

    
    public function deleteCategory($id)
    {
        $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateCategory($id, $nombre)
    {
        $stmt = $this->db->prepare("UPDATE categorias SET nombre = :nombre WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        return $stmt->execute();
    }
}