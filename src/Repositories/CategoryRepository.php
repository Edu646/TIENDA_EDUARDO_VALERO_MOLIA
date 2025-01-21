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
}