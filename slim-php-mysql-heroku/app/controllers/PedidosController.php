<?php
require_once './models/Pedido.php';
require_once './models/ProductoPedido.php';
require_once './interfaces/IApiUsable.php';

class PedidosController extends Pedido implements IApiUsable
{
    public static function  generateRandomPedido()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function CargarUno($request, $response, $args)
    {


        $parametros = $request->getParsedBody();
        if (Mesa::verificarMesa($parametros['idMesa'])) {

            $estado = $parametros['estado'];
            $idMesa = $parametros['idMesa'];
            $nombreCliente = $parametros['nombreCliente'];
            $pedidoCliente = Self::generateRandomPedido();
            $p = new Pedido();
            $p->estado = $estado;
            $p->idMesa = $idMesa;
            $p->duracion = "indefinida";
            $p->nombreCliente = $nombreCliente;
            $p->pedidoCliente = $pedidoCliente;

            $p->crearPedido();

            $payload = json_encode(array("mensaje" => "Pedido creado con exito, numero de pedido $pedidoCliente"));
        } else $payload = json_encode(array("mensaje" => "Id de mesa invalido"));


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


    public function CargarProductos($request, $response, $args)
    {
        if (isset($request->getParsedBody()['listaProductos'], $request->getParsedBody()['codigoPedido'])) {
            $pedido = Pedido::VerificarPedido($request->getParsedBody()['codigoPedido']);

            if ($pedido != false)
                $productos = $request->getParsedBody()['listaProductos'];
            Pedido::CargarProductosPedido($pedido->id, $pedido->idMesa, $productos);
            $payload = json_encode(array("mensaje" => "Productos cargados con exito"));
        } else $payload = json_encode(array("mensaje" => "Faltan datos"));


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public static function productosCargados()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productopedido WHERE duracion IS NULL;");

        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }


    public function obtenerDemora($request, $response, $args)
    {
        if (SELF::productosCargados() == false) {
            $demora = Pedido::obtenerDuracion($args["idMesa"], $args["idPedido"]);
            $payload = json_encode(array("demora" => $demora));
        } else $payload = json_encode(array("demora" => "indefinida"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function obtenerCuenta($request, $response, $args)
    {
        $cuenta =  Pedido::calcularCuenta($args["idMesa"], $args["idPedido"]);

        $payload = json_encode(array("Precio de la cuenta" => " El valor a pagar es $$cuenta"));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
