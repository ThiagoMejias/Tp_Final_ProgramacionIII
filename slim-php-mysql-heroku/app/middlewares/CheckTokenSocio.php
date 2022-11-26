<?php

use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\ResponseEmitter;


class CheckTokenSocio
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $header = $request->getHeaderLine("Authorization");
        if (!empty($header)) {

            if ($header != null) {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::ObtenerData($token);
                if ($data->tipo == "socio") {
                    $response = $handler->handle($request);
                } else  $response->getBody()->write('Se necesita ser socio');
            }
        } else {
            $response->getBody()->write('Ingrese Token');
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}
