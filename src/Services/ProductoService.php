<?php 
namespace Services;

use Repositories\ProductoRepository;
use Models\Producto;

class ProductoService {
    private $productoRepository;

    public function __construct(ProductoRepository $productoRepository)
    {
        $this->productoRepository = $productoRepository;
    }

    public function addProducto(Producto $producto)
    {
        return $this->productoRepository->insert($producto);
    }

    public function getAllProductos()
    {
        return $this->productoRepository->findAll();
    }
}