<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $estado = $parametros['estado'];



    $mesa = new Mesa();
    $mesa->estado = $estado;
    $mesa->crearMesa();

    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Mesa::obtenerTodos();
    $payload = json_encode(array("listaUsuario" => $lista));
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
