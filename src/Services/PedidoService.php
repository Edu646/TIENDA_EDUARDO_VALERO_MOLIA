<?php

namespace Services;

use Repositories\PedidoRepository;
use Repositories\ProductoRepository;
use Models\Pedido;
use Lib\Database;

class PedidoService
{
    private PedidoRepository $pedidoRepository;
    private ProductoRepository $productoRepository;

    public function __construct()
    {
        $db = Database::getConnection();
        $this->pedidoRepository = new PedidoRepository($db);
        $this->productoRepository = new ProductoRepository($db);
    }

    public function crearPedido(int $usuario_id, string $provincia, string $localidad, string $direccion, array $carrito): int
    {
        $pedidoId = $this->pedidoRepository->insert($usuario_id, $provincia, $localidad, $direccion, $carrito);

        foreach ($carrito as $item) {
            $this->pedidoRepository->insertLineaPedido($pedidoId, $item['producto_id'], $item['cantidad']);
            $this->productoRepository->reducirStock($item['producto_id'], $item['cantidad']);
        }

        return $pedidoId;
    }

    /**
     * Obtiene los pedidos de un usuario específico.
     */
    public function obtenerPedidosPorUsuario(int $usuario_id): array
    {
        return $this->pedidoRepository->obtenerPedidosPorUsuario($usuario_id);
    }

    /**
     * Obtiene todos los pedidos (para administradores).
     */
    public function obtenerTodosLosPedidos(): array
    {
        return $this->pedidoRepository->obtenerTodosLosPedidos();
    }

    /**
     * Actualiza el estado de un pedido.
     */
    public function actualizarEstadoPedido(int $pedidoId, string $nuevoEstado): bool
    {
        return $this->pedidoRepository->actualizarEstadoPedido($pedidoId, $nuevoEstado);
    }
}
