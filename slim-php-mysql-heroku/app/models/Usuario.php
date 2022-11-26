<?php

class Usuario
{
    public $id;
    public $usuario;
    public $clave;
    public $perfil;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, clave,perfil) VALUES (:usuario,:clave,:perfil)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':usuario', $this->usuario);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':perfil', $this->perfil);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();
        return  $consulta->fetchObject('Usuario');
    }


    public static function ObtenerPerfil($request)
    {
        $header = $request->getHeaderLine("Authorization");
        if ($header != null) {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            return $data->tipo;
        }
    }
    public static function ObtenerId($request)
    {
        $header = $request->getHeaderLine("Authorization");
        if ($header != null) {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            return $data->id;
        }
    }

    // public static function modificarUsuario($id, $nombre, $clave)
    // {
    //     $objAccesoDato = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave WHERE id = :id");
    //     $consulta->bindValue(':usuario', $nombre, PDO::PARAM_STR);
    //     $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
    //     $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    //     $consulta->execute();
    // }

    // public static function borrarUsuario($usuario)
    // {
    //     $objAccesoDato = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
    //     $fecha = new DateTime(date("d-m-Y"));
    //     $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
    //     $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
    //     $consulta->execute();
    // }


    // public static function verificarUsuario($usuario, $clave)
    // {

    //     $db = AccesoDatos::obtenerInstancia();
    //     $consulta = $db->prepararConsulta("SELECT * from usuarios where usuario = :usuario AND clave = :clave");
    //     $consulta->bindValue(':usuario', $usuario);
    //     $consulta->bindValue(':clave', $clave);
    //     $consulta->execute();
    //     $user = $consulta->fetch(PDO::FETCH_BOTH);
    //     if ($user)
    //         return true;
    //     return false;
    // }
}
