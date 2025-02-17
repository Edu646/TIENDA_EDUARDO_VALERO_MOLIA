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

public function getProductosByCategoria($categoriaId) {
    // Obtén todos los productos de la misma categoría
    $query = "SELECT * FROM productos WHERE categoria_id = :categoria_id";
    $stmt = $this->productoRepository->getConnection()->prepare($query);
    $stmt->bindParam(':categoria_id', $categoriaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





    

}    