<?php

class Producto
{
    public $id;
    public $perfilUsuario;
    public $descripcion;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Productos (perfilUsuario, descripcion) VALUES (:perfilUsuario, :descripcion)");

        $consulta->bindValue(':perfilUsuario', $this->perfilUsuario);
        $consulta->bindValue(':descripcion', $this->descripcion);


        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }
}
