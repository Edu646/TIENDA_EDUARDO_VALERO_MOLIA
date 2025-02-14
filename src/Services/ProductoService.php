<?php 
namespace Services;

use Repositories\ProductoRepository;
use Models\Producto;
use \PDO;

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

    public function deleteProducto($producto_id) {
        return $this->productoRepository->deleteProductoById($producto_id);
    }


    public function getProductoById($producto_id) {
        return $this->productoRepository->findById($producto_id);
    }

    

}    