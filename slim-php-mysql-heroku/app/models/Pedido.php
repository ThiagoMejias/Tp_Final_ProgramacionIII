<?php

class Pedido
{
    public $id;
    public $idMesa;
    public $nombreCliente;
    public $duracion;
    public $estado;
    public $pedidoCliente;
    public $urlFoto;


    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMesa,nombreCliente,estado,pedidoCliente) VALUES (:estado)");

        $consulta->bindValue(':estado', $this->estado);
        $consulta->bindValue(':idMesa', $this->idMesa);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente);
        $consulta->bindValue(':pedidoCliente', $this->pedidoCliente);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
}
