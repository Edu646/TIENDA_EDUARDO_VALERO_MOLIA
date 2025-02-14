<?php

namespace Models;

class Pedido
{
    private ?int $id;
    private int $usuario_id;
    private string $provincia;
    private string $localidad;
    private string $direccion;
    private float $coste;

    public function __construct(?int $id, int $usuario_id, string $provincia, string $localidad, string $direccion, float $coste)
    {
        $this->id = $id;
        $this->usuario_id = $usuario_id;
        $this->provincia = $provincia;
        $this->localidad = $localidad;
        $this->direccion = $direccion;
        $this->coste = $coste;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    public function getProvincia(): string
    {
        return $this->provincia;
    }

    public function getLocalidad(): string
    {
        return $this->localidad;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function getCoste(): float
    {
        return $this->coste;
    }
}