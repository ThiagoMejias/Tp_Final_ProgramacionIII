<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class CheckEstadosMesas
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $params = $request->getParsedBody();

        if (isset($params['estado'])) {

            if (
                $params['estado'] == "cliente esperando pedido" || $params['estado'] == "cliente comiendo"
                || $params['estado'] == "cliente pagando" || $params['estado'] == "cerrada"
            ) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(("Estado invalido."));
            }
        } else $response->getBody()->write(("Faltan datos"));

        return $response;
    }
}
