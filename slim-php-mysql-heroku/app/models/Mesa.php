<?php

class Mesa
{
    public $id;
    public $estado;

    public function crearMesa()
    {


        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado) VALUES (:estado)");

        $consulta->bindValue(':estado', $this->estado);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function verificarMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos where id = :id");
        $consulta->bindValue(':id', $id);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }
    public function modificarEstado($idMesa, $estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesas set estado = :estado WHERE id = :idMesa;");
        $consulta->bindValue(':idMesa', $idMesa);
        $consulta->bindValue(':estado', $estado);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }
}
