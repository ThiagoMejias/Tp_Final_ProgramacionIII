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
        if (isset($params['perfil'], $params['descripcion'], $params['precio'])) {

            if ($params['perfil'] != "" && $params['descripcion'] != "" && $params['precio'] != "") {
                $response = $handler->handle($request);
            } else   $response->getBody()->write(("Campo vacio."));
        } else $response->getBody()->write(("Faltan datos"));

        return $response;
    }
}
