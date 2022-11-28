<?php

class Producto
{
    public $id;
    public $perfil;
    public $descripcion;
    public $precio;


    public function __construct($id = "", $descripcion = "", $precio = "", $perfil = "")
    {
        $this->id = $id;
        $this->perfil = $perfil;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
    }


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
        $consulta->bindValue(':precio', $this->precio);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public function modificarProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE Productos set perfil = :perfil
        ,descripcion = :descripcion, precio = :precio where id = :id");

        $consulta->bindValue(':perfil', $this->perfil);
        $consulta->bindValue(':descripcion', $this->descripcion);
        $consulta->bindValue(':precio', $this->precio);
        $consulta->bindValue(':id', $this->id);

        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function borrarProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM productos WHERE id = :id");

        $consulta->bindValue(':id', $id);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function mostrarDatos($array)
    {

        if (!empty($array)) {
            $retorno = "";
            foreach ($array as $producto) {
                $retorno .= $producto->id . ',' . $producto->descripcion . ',' . $producto->precio . ',' . $producto->perfil . PHP_EOL;
            }
            return $retorno;
        }
        $retorno = "Esta Vacio";
        return $retorno;
    }

    public static function altaProductosCsv($productos, $path)
    {
        $file = fopen($path,  "a+");

        if ($file != null) {

            fwrite($file, $productos);
            $retorno = true;
        } else  $retorno = false;

        fclose($file);
        return $retorno;
    }
    public static function crearCsv()
    {

        $lista = self::mostrarDatos(self::obtenerTodos());

        if (self::altaProductosCsv($lista, "./productos.csv"))
            return true;

        return false;
    }

    public static function actualizarCsv($archivo)
    {

        $lista = Producto::leerProductosCsv($archivo);
        foreach ($lista as $p) {
            var_dump($p);
            if (!self::verificarProducto($p->descripcion)) {
                $p->crearProducto();
            }
        }
    }

    public static function leerProductosCsv($archivo)
    {
        $lista = array();
        if ($archivo != null)
            while (!feof($archivo) && ($linea = fgetcsv($archivo)) !== false) {
                array_push($lista, new Producto($linea[0], $linea[1], $linea[2], $linea[3]));
            }
        fclose($archivo);
        return $lista;
    }
}
