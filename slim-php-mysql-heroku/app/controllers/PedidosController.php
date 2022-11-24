<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidosController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $idMesa = $parametros['idMesa'];
        $nombreCliente = $parametros['nombreCliente'];
        $pedidoCliente = $parametros['pedidoCliente'];

        $p = new Pedido();
        $p->estado = $estado;
        $p->idMesa = $idMesa;
        $p->nombreCliente = $nombreCliente;
        $p->pedidoCliente = $pedidoCliente;

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
