<?php 
namespace Models;
use PDO;
class Producto{
    private $id;
    private $categoria_id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $stock;
    private $oferta;
    private $fecha;
    private $imagen;

    public function __construct($nombre = null, $id = null, $categoria_id = null, $descripcion = null, $precio = null, $stock = null, $oferta = null, $fecha = null, $imagen = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->setCategoriaId($categoria_id);
        $this->descripcion = $descripcion;
        $this->setPrecio($precio);
        $this->stock = $stock;
        $this->setOferta($oferta);
        $this->fecha = $fecha;
        $this->imagen = $imagen;
    }

    public static function fromArray(array $data) {
        return new self(
            $data['nombre'] ?? null,
            $data['id'] ?? null,
            $data['categoria_id'] ?? null,
            $data['descripcion'] ?? null,
            $data['precio'] ?? null,
            $data['stock'] ?? null,
            $data['oferta'] ?? null,
            $data['fecha'] ?? null,
            $data['imagen'] ?? null
        );
    }

    // Getters and Setters
    public function getId() {
        return $this->id;
    }

    public function getCategoriaId() {
        return $this->categoria_id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getOferta() {
        return $this->oferta;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setCategoriaId($categoria_id) {
       
        $this->categoria_id = $categoria_id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setPrecio($precio) {
        if (!is_numeric($precio)) {
            throw new \InvalidArgumentException("El precio debe ser un valor numérico.");
        }
        $this->precio = $precio;
    }
    
    public function setStock($stock) {
        if (!is_numeric($stock)) {
            throw new \InvalidArgumentException("El stock debe ser un valor numérico.");
        }
        $this->stock = $stock;
    }
    
    public function setOferta($oferta) {
        if (!is_numeric($oferta)) {
            throw new \InvalidArgumentException("La oferta debe ser un valor numérico.");
        }
        $this->oferta = $oferta;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }
}
