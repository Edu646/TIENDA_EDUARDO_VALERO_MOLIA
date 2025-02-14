<?php
namespace Repositories;

use PDO;
use Models\Pedido;

class PedidoRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insert(int $usuario_id, string $provincia, string $localidad, string $direccion, array $carrito): int
    {
        $stmt = $this->db->prepare("INSERT INTO pedidos (usuario_id, provincia, localidad, direccion, coste, estado, fecha, hora) VALUES (:usuario_id, :provincia, :localidad, :direccion, :coste, 'pendiente', CURDATE(), CURTIME())");
        $coste = array_sum(array_column($carrito, 'precio_total'));
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':provincia', $provincia);
        $stmt->bindParam(':localidad', $localidad);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':coste', $coste);
        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function insertLineaPedido(int $pedidoId, int $productoId, int $unidades): bool
    {
        $stmt = $this->db->prepare("INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades) VALUES (:pedido_id, :producto_id, :unidades)");
        $stmt->bindParam(':pedido_id', $pedidoId);
        $stmt->bindParam(':producto_id', $productoId);
        $stmt->bindParam(':unidades', $unidades);
        return $stmt->execute();
    }
}