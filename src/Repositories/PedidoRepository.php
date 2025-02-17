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

    public function obtenerPedidosPorUsuario(int $usuario_id): array
    {
        $stmt = $this->db->prepare("
            SELECT p.id, p.fecha, p.estado, p.provincia, p.localidad, p.direccion, 
                   SUM(lp.unidades * pr.precio) AS total
            FROM pedidos p
            LEFT JOIN lineas_pedidos lp ON p.id = lp.pedido_id
            LEFT JOIN productos pr ON lp.producto_id = pr.id
            WHERE p.usuario_id = ?
            GROUP BY p.id
        ");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los pedidos (para administradores).
     */
    public function obtenerTodosLosPedidos(): array
    {
        $stmt = $this->db->query("
            SELECT p.id, p.fecha, p.estado, p.usuario_id, u.email, p.provincia, p.localidad, p.direccion,
                   GROUP_CONCAT(lp.producto_id) AS productos
            FROM pedidos p
            LEFT JOIN usuarios u ON p.usuario_id = u.id
            LEFT JOIN lineas_pedidos lp ON p.id = lp.pedido_id
            GROUP BY p.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza el estado de un pedido.
     */
    public function actualizarEstadoPedido(int $pedidoId, string $nuevoEstado): bool
    {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        return $stmt->execute([$nuevoEstado, $pedidoId]);
    }
}