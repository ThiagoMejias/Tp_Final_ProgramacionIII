<?php

require_once "Producto.php";
require_once "Files.php";


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
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (idMesa,nombreCliente,estado,pedidoCliente) VALUES (:idMesa,:nombreCliente,:estado,:pedidoCliente)");

        $consulta->bindValue(':estado', $this->estado);
        echo $this->idMesa;
        $consulta->bindValue(':idMesa', $this->idMesa);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente);
        $consulta->bindValue(':pedidoCliente', $this->pedidoCliente);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerDuracion($idMesa, $idPedidoCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT Max(pp.duracion) as duracion from pedido p 
        inner join productopedido pp on pp.idPedido = p.id where p.pedidoCliente = :idPedidoCliente and p.idMesa = :idMesa;");
        $consulta->bindValue(':idMesa', $idMesa);
        $consulta->bindValue(':idPedidoCliente', $idPedidoCliente);

        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public static function ObtenerPedidoPorId($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido where id = :id");
        $consulta->bindValue(':id', $idPedido);
        $consulta->execute();
        return $consulta->fetchObject('Pedido');
    }


    public static function VerificarPedido($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido where pedidoCliente = :codigoPedido and estado != 'cerrado';");
        $consulta->bindValue(':codigoPedido', $codigoPedido);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function vincularImagen($img, $idPedido)
    {

        $pedido = self::ObtenerPedidoPorId($idPedido);
        if ($pedido != false) {
            $archivo = new Files("../Mesas");
            $nombre = "/Mesa_" . $pedido->idMesa . "pedido_" . $pedido->id . ".jpg";
            $archivo->subirArchivo($nombre);
            self::setUrlImg($nombre, $idPedido);
            return true;
        }
        return false;
    }

    public static function setUrlImg($urlFoto, $idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedido set urlFoto = :urlFoto where id = :idPedido");
        $consulta->bindValue(':urlFoto', $urlFoto);
        $consulta->bindValue(':idPedido', $idPedido);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function CargarProductosPedido($idPedido, $idMesa, $productos)
    {
        $retorno = true;
        $productoPedido = new ProductoPedido();

        foreach ($productos as $p) {


            $orden = Producto::verificarProducto($p["descripcion"]);

            if ($orden != false) {

                $productoPedido->descripcion = $orden->descripcion;
                $productoPedido->estado = "pendiente";
                $productoPedido->idPedido = $idPedido;
                $productoPedido->idProducto = $orden->id;
                $productoPedido->idMesa = $idMesa;
                $productoPedido->crearProductoPedido();
            } else $retorno = false;
        }
        return $retorno;
    }

    public function calcularCuenta($idMesa, $pedidoCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(pr.precio) FROM pedido p 
        inner join productopedido pp on pp.idPedido = p.id
        INNER JOIN productos pr on pp.idProducto = pr.id
        where (p.pedidoCliente = :pedidoCliente and p.idMesa = :idMesa);");
        $consulta->bindValue(':idMesa', $idMesa);
        $consulta->bindValue(':pedidoCliente', $pedidoCliente);

        $consulta->execute();

        return $consulta->fetch()[0];
    }
}
