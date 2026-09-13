<?php

namespace Api\Middlewares\Professor;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Api\Http\ErrorResponse;

class ValidateProfessorLoginBody implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        error_log("🟠  ValidateProfessorLoginBody::process()");

        $body = $request->getBody()->getContents();

        $objPHP = json_decode($body);

        if (!isset($objPHP->professor)) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "O campo 'professor' é obrigatório!"
                ]
            );
        }

        $professor = $objPHP->professor;

        if (
            !isset($professor->prof_nome) ||
            empty(trim($professor->prof_nome))
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "O campo 'prof_nome' é obrigatório!"
                ]
            );
        }

        if (!is_string($professor->prof_nome)) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "Nome inválido!"
                ]
            );
        }

        if (
            !isset($professor->prof_id) ||
            empty(trim($professor->prof_id))
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "O campo 'prof_id' é obrigatório!"
                ]
            );
        }

        return $handler->handle($request);
    }
}