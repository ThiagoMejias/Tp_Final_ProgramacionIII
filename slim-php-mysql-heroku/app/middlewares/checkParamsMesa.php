<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class CheckParamsMesa
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $params = $request->getParsedBody();
        if (isset($params['estado'])) {

            if ($params['estado'] == "") {
                $response->getBody()->write(("Campo vacio."));
            } else $response = $handler->handle($request);
        } else $response->getBody()->write(("Faltan datos"));

        return $response;
    }
}
