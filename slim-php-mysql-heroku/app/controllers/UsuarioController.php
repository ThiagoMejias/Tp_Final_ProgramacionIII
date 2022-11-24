<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usuario = $parametros['usuario'];
    $clave = $parametros['clave'];


    // Creamos el usuario
    $usr = new Usuario();
    $usr->usuario = $usuario;
    $usr->clave = $clave;
    $usr->perfil = $parametros['perfil'];
    $usr->crearUsuario();

    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::obtenerTodos();
    $payload = json_encode(array("listaUsuario" => $lista));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }



  public function Login($request, $response, $args)
  {
    $params = $request->getParsedBody();

    $user = Usuario::obtenerUsuario($params['usuario']);
    if ($user) {
      if (password_verify($params['clave'], $user->clave) || $params['clave'] == $user->clave) {

        $datos = array('tipo' => $user->perfil);
        $token = AutentificadorJWT::CrearToken($datos);

        $payload = json_encode(array('TOKEN:' => $token));

        $response->getBody()->write($payload);

        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
      }
    }
    $response->getBody()->write("Usuario inexistente");
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
