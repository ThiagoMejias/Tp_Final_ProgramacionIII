<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class CheckParamsProducto
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $params = $request->getParsedBody();
        if (isset($params['estado'], $params['idMesa'], $params['nombreCliente'], $params['pedidoCliente'])) {

            if ($params['estado'] != "" && $params['idMesa'] != "" && $params['nombreCliente'] != "" && $params['pedidoCliente'] != "") {
                $response->getBody()->write(("Campo vacio."));
            }
        } else $response->getBody()->write(("Faltan datos"));

        return $response;
    }
}
