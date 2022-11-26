<?php
require_once './models/Pedido.php';
require_once './models/Usuario.php';
require_once './models/ProductoPedido.php';
require_once './interfaces/IApiUsable.php';

class ProductoPedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        return 0;
    }
    public function TraerTodos($request, $response, $args)
    {
        $perfil = Usuario::ObtenerPerfil($request);

        if ($perfil != 'mozo') {
            $lista = ProductoPedido::obtenerTodosPorPerfil($perfil);
            $listaPorUsuario = ProductoPedido::obtenerTodosPorUsuario(Usuario::ObtenerId($request));
            $listaFinal = array_merge($lista, $listaPorUsuario);
        } else $listaFinal = ProductoPedido::obtenerTodosListosParaServir();
        $payload = json_encode(array("listaPedidos" => $listaFinal));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($request, $response)
    {
        $params = $request->getParsedBody();
        if (isset($params['id'], $params['duracion'], $params['estado'])) {

            if (ProductoPedido::verificarPedidoConPerfil($params['id'], Usuario::ObtenerPerfil($request))) {
                $lista = ProductoPedido::modificarProductoPedido($params['id'], Usuario::ObtenerId($request), $params['duracion'], $params['estado']);
                $payload = json_encode(array("Mensaje" => "Pedido tomado"));
            } else $payload = json_encode(array("Mensaje" => "Pedido no correspondido!"));
        } else  $payload = json_encode(array("Mensaje" => "faltan datos"));


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
