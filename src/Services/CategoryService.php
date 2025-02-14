<?php

namespace Services;

use Repositories\CategoryRepository;
use Models\Category;

class CategoryService
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function addCategory($nombre)
    {
        $category = new Category($nombre);
        return $this->categoryRepository->insert($category);
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->findAll();
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepository->deleteCategory($id);
    }

    public function updateCategory($id, $nombre)
    {
        return $this->categoryRepository->updateCategory($id, $nombre);
    }
}