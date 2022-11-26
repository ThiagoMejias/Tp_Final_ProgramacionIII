<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class CheckParamsPedido
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $params = $request->getParsedBody();
        if (isset($params['nombreCliente'], $params['estado'], $params['idMesa'])) {

            if ($params['nombreCliente'] != "" && $params['estado'] != "" && $params['idMesa'] != "") {
                $response = $handler->handle($request);
            } else   $response->getBody()->write(("Campo vacio."));
        } else $response->getBody()->write(("Faltan datos"));

        return $response;
    }
}
