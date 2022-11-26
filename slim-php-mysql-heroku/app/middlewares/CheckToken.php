<?php

use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\ResponseEmitter;


class CheckToken
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $header = $request->getHeaderLine("Authorization");
        if (!empty($header)) {
            try {
                $header = $request->getHeaderLine("Authorization");
                if ($header != null) {
                    $token = trim(explode("Bearer", $header)[1]);
                }
                AutentificadorJWT::VerificarToken($token);
                $response = $handler->handle($request);
            } catch (\Throwable $th) {
                $response->getBody()->write($th->getMessage());
                $response->getBody()->write('Token invalido');
            }
        } else {
            $response->getBody()->write('Ingrese Token');
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}
