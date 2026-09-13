<?php

namespace Api\Middlewares\Professor;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;

use Api\Http\ErrorResponse;
use Api\Http\MeuTokenJWT;

class ValidateProfessorToken implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        error_log("🟠  ValidateProfessorToken::process()");
        $authorization = $request->getHeaderLine('Authorization');

        if (empty($authorization)) {
            throw new ErrorResponse(
                httpCode: 401,
                message: "Acesso não autorizado",
                error: ["message" => "Token de autenticação não informado"]
            );
        }

        if (!str_starts_with($authorization, 'Bearer ')) {
            throw new ErrorResponse(
                httpCode: 401,
                message: "Acesso não autorizado",
                error: ["message" => "Formato do token inválido"]
            );
        }

        $jwt = new MeuTokenJWT();
        if (!$jwt->validateToken($authorization)) {
            throw new ErrorResponse(
                httpCode: 401,
                message: "Acesso não autorizado",
                error: ["message" => "Token inválido ou expirado"]
            );
        }
        //adiciona o payload na requisição
        // 
        $request = $request->withAttribute('jwtPayload', $jwt->getPayload());
        return $handler->handle($request);
    }
}