<?php

class ProductoPedido
{
    public $idPedido;
    public $idProducto;
    public $descripcion;
    public $estado;
    public $duracion;
    public $idMesa;
    public $idEmpleado;





    public function crearProductoPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productoPedido (idPedido,idProducto,descripcion,estado, idMesa) 
        VALUES (:idPedido, :idProducto, :descripcion, :estado,:idMesa)");

        $consulta->bindValue(':idPedido', $this->idPedido);
        $consulta->bindValue(':idProducto', $this->idProducto);
        $consulta->bindValue(':descripcion', $this->descripcion);
        $consulta->bindValue(':estado', $this->estado);
        $consulta->bindValue(':idMesa', $this->idMesa);


        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function verificarPedidoConPerfil($id, $perfil)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        if ($perfil != 'socio') {
            $consulta = $objAccesoDatos->prepararConsulta("SELECT pp.* FROM productopedido pp 
            inner join productos p on p.id = pp.idProducto where pp.id = :id and p.perfil = :perfil and pp.estado != 'listo para servir';");
            $consulta->bindValue(':perfil', $perfil);
        } else $consulta = $objAccesoDatos->prepararConsulta("SELECT pp.* FROM productopedido pp 
        inner join productos p on p.id = pp.idProducto where pp.id = :id and pp.estado != 'listo para servir';");

        $consulta->bindValue(':id', $id);

        $consulta->execute();

        if ($consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido') != false) return true;
        return false;
    }

    public  static function modificarProductoPedido($id, $idEmpleado, $duracion, $estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("update  productopedido
        set estado  = :estado, duracion = :duracion, idEmpleado = :idEmpleado where id = :id;");
        $consulta->bindValue(':estado', $estado);
        $consulta->bindValue(':duracion', $duracion);
        $consulta->bindValue(':idEmpleado', $idEmpleado);
        $consulta->bindValue(':id', $id);
        $consulta->execute();
    }


    public static function obtenerTodosPorPerfil($perfil)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pp.* FROM productopedido pp inner JOIN productos p on pp.idProducto = p.id where( p.perfil = :perfil AND pp.estado != 'listo para servir');");
        $consulta->bindValue(':perfil', $perfil);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }
    public static function obtenerTodosPorUsuario($idEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productopedido pp WHERE (idEmpleado = :idEmpleado AND pp.estado != 'listo para servir');");
        $consulta->bindValue(':idEmpleado', $idEmpleado);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }
    public static function obtenerTodosListosParaServir()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productopedido  WHERE (estado = 'listo para servir');");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }
}
