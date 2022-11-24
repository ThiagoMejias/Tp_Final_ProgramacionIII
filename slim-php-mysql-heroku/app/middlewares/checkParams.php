<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class CheckParams
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $params = $request->getParsedBody();
        if (isset($params['perfil'], $params['usuario'], $params['clave'])) {

            if ($params['perfil'] != "" && $params['usuario'] != "" && $params['clave'] != "") {
                $response->getBody()->write(("Campo vacio."));
            }
        } else $response->getBody()->write(("Faltan datos"));

        return $response;
    }
}
