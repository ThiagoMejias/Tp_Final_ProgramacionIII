<?php

class Csv
{

    // public static function leerProductosCsv($path)
    // {
    //     $lista = array();
    //     $archivo =  fopen($path, "r");
    //     if ($archivo != null)
    //         while (!feof($archivo) && ($linea = fgetcsv($archivo)) !== false) {
    //             array_push($lista, new usuario($linea[0], $linea[1], $linea[2], $linea[3]));
    //         }
    //     fclose($archivo);
    //     return $lista;
    // }
    // public static function descargarProductosCsv()
    // {
    //     if (!isset($_POST['nombre']) && !isset($_POST['clave']) && isset($_POST['apellido']) && !isset($_POST['email'])) {
    //         $usuario =  new Usuario($_POST['nombre'], isset($_POST['apellido']), $_POST['clave'], $_POST['email'], $_POST['localidad']);
    //         return  Usuario::AltaUsuarioCsv($usuario, "./usarios.csv");
    //     }
    //     echo "no se puede instanciar  y guardar";
    //     return null;
    // }

    // public function cargarDatos()
    // {
    //     if (!isset($_FILES['csv'])) {
    //         fwrite()
    //     }
    // }

    // public static function altaUsuarioCsv($usuario, $path)
    // {
    //     $file = fopen($path,  "a+");

    //     if ($file != null) {
    //         $data = Usuario::mostrarDatosUsuario($usuario);
    //         fwrite($file, $data);
    //         $retorno = true;
    //     } else {
    //         $retorno = false;
    //     }
    //     fclose($file);
    //     return $retorno;
    // }
}
