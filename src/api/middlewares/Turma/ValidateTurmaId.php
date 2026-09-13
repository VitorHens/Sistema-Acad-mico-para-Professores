<?php

namespace Api\Middlewares\Turma;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Routing\RouteContext;
use Api\Http\ErrorResponse;

class ValidateTurmaId implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        if (!$route) {
            throw new ErrorResponse(400, "Erro na validação de dados", ["message" => "Rota não encontrada!"]);
        }

        $routeArgs = $route->getArguments();
        if (!isset($routeArgs['tur_id']) || $routeArgs['tur_id'] === "") {
            throw new ErrorResponse(400, "Erro na validação de dados", ["message" => "O parâmetro 'tur_id' é obrigatório!"]);
        }

        return $handler->handle($request);
    }
}
