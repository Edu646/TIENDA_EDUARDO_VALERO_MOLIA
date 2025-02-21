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


    public function getProductoById($id) {
        return $this->productoRepository->findProductoById($id);
    }

    public function updateProducto(Producto $producto) {
        // Validar que el producto tiene datos correctos
        if (empty($producto->getNombre()) || empty($producto->getCategoriaId())) {
            throw new \InvalidArgumentException('Nombre y categoría son obligatorios.');
        }

        // Verificar si el producto existe antes de actualizarlo
        $existingProduct = $this->productoRepository->findProductoById($producto->getId());
        if (!$existingProduct) {
            throw new \Exception('Producto no encontrado.');
        }

        // Llamar al repositorio para actualizar el producto en la base de datos
        return $this->productoRepository->update($producto);
    }

    // ProductoService
  public function getAllProductosapi() {
    return $this->productoRepository->getAllProductos();
}



public function delete($producto_id) {
    return $this->productoRepository->delete($producto_id);
}


public function getById($id) {
    return $this->productoRepository->getById($id);
}
    

}    