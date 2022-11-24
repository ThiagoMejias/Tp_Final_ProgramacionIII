<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class CheckPerfil
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $params = $request->getParsedBody();
        if (isset($params['perfil'])) {

            if ($params['perfil'] != "") {
                if (
                    $params['perfil'] == 'bartender' || $params['perfil'] == 'cervecero' || $params['perfil'] == 'cocinero' ||
                    $params['perfil'] == 'mozo' || $params['perfil'] == 'socio'
                ) {
                    $response = $handler->handle($request);
                } else $response->getBody()->write("Perfil invalido");
            } else $response->getBody()->write(("Campo vacio."));
        } else $response->getBody()->write(("Faltan datos"));

        return $response;
    }
}
