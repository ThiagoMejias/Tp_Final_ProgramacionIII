<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class CheckEstadosProductosPedidos
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $params = $request->getParsedBody();

        if (isset($params['estado'])) {

            if ($params['estado'] == "en preparacion" || $params['estado'] == "listo para servir") {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(("Estado invalido."));
            }
        } else $response->getBody()->write(("Faltan datos"));

        return $response;
    }
}
