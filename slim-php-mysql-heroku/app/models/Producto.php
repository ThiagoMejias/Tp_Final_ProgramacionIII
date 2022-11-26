<?php

class Producto
{
    public $id;
    public $perfil;
    public $descripcion;
    public $precio;

    public static function verificarProducto($descripcion)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos where descripcion = :descripcion");
        $consulta->bindValue(':descripcion', $descripcion);
        $consulta->execute();
        return $consulta->fetchObject('Producto');
    }


    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Productos (perfil, descripcion,precio) VALUES (:perfil, :descripcion, :precio)");

        $consulta->bindValue(':perfil', $this->perfil);
        $consulta->bindValue(':descripcion', $this->descripcion);
        $consulta->bindValue(':precio', $this->descripcion);



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
