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



  // public function TraerUno($request, $response, $args)
  // {
  //   // Buscamos usuario por nombre
  //   $usr = $args['usuario'];
  //   $usuario = Usuario::obtenerUsuario($usr);
  //   $payload = json_encode($usuario);

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }


  // public function ModificarUno($request, $response, $args)
  // {

  //   $parametros = $request->getParsedBody();
  //   $id = $parametros['id'];
  //   $clave = $parametros['clave'];
  //   $nombre = $parametros['nombre'];
  //   Usuario::modificarUsuario($id, $nombre, $clave);

  //   $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }


  // public function BorrarUno($request, $response, $args)
  // {
  //   $parametros = $request->getParsedBody();

  //   $usuarioId = $parametros['usuarioId'];
  //   Usuario::borrarUsuario($usuarioId);

  //   $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }

  // public function Login($request, $response, $args)
  // {
  //   $params = $request->getParsedBody();

  //   $user = Usuario::obtenerUsuario($params['usuario']);

  //   if (password_verify($params['clave'], $user->clave))
  //     $response->getBody()->write("Logeado Correctamente");
  //   else {
  //     $response->getBody()->write("Error en clave o usuario");
  //   }

  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }
}
