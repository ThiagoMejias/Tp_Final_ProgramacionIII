<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $perfil = $parametros['perfil'];
    $descripcion = $parametros['descripcion'];

    $p = new Producto();
    $p->perfil = $perfil;
    $p->descripcion = $descripcion;
    $payload = json_encode(array("mensaje" => "Producto creado con exito"));
    $p->crearProducto();

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Producto::obtenerTodos();

    $payload = json_encode(array("listaProductos" => $lista));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function generarCsv($request, $response, $args)
  {
    if (Producto::crearCsv())
      $payload =  json_encode("Archivo creado correctamente");
    else $payload =  json_encode("Algo ocurrio mal generando el archivo");
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }




  public function cargarCsv($request, $response, $args)
  {
    if (isset($_FILES['csv'])) {
      $archivo = fopen($_FILES['csv']['tmp_name'], "r");
      if ($archivo != null) {
        Producto::actualizarCsv($archivo);
        $payload =  json_encode("Productos actualizados");
      }
    } else $payload =  json_encode("Archivo no cargado");

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
