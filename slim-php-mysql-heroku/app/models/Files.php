<?php

class Files
{
    public $path;
    public $destino;


    public function __construct($path)
    {
        if (!file_exists($path))
            mkdir($path, 0777, true);

        $this->path = $path;
    }

    private function cambiarNombre($nombre)
    {
        $nombreSinPuntos = explode('.', $_FILES["img"]["name"]);
        $nombreSinPuntos[0] = $nombre;
        $this->destino = $this->path . $nombreSinPuntos[0] . "." . $nombreSinPuntos[1];
    }
    public function subirArchivo($nombreArchivo)
    {
        self::cambiarNombre($nombreArchivo);
        move_uploaded_file($_FILES["img"]["tmp_name"], $this->destino);
    }

    public static function moverImagen($origen, $destino, $fileName)
    {
        if (!file_exists($destino))
            mkdir($destino, 0777, true);

        return rename($origen . $fileName, $destino . $fileName);
    }
}
