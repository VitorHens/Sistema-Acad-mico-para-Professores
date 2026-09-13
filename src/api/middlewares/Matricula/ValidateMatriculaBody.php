<?php

namespace Api\Middlewares\Matricula;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Api\Http\ErrorResponse;

/**
 * Middleware responsável por validar o corpo (body)
 * das requisições relacionadas ao recurso Matricula.
 *
 * Objetivo:
 * Garantir que os dados mínimos necessários estejam presentes
 * antes de a requisição chegar ao Controller.
 *
 * Se algum campo estiver inválido ou ausente,
 * uma exceção ErrorResponse será lançada com HTTP 400.
 *
 * Estrutura esperada do JSON:
 *
 * {
 *   "matricula": {
 *     "nomeMatricula": "João Silva",
 *     "email": "joao@email.com",
 *     "senha": "123456",
 *     "recebeValeTransporte": 1,
 *     "turma": {
 *       "tur_id": 2
 *     }   
 *     "aluno": {
 *       "alu_id": 3
 *     }
 *   }
 * }
 */
class ValidateMatriculaBody implements MiddlewareInterface
{
    /**
     * Método executado automaticamente pelo Slim
     * antes da requisição seguir para o próximo middleware
     * ou para o Controller.
     *
     * Fluxo:
     * 1. Lê o body enviado na requisição
     * 2. Valida estrutura principal
     * 3. Valida campos obrigatórios
     * 4. Valida regras específicas
     * 5. Libera continuidade da execução
     *
     * @param Request $request Requisição HTTP recebida
     * @param RequestHandler $handler Próximo item da fila
     *
     * @return Response
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        /**
         * Lê o JSON bruto enviado no body
         * e converte para objeto stdClass.
         *
         * Exemplo:
         * $objPHP->Matricula->nomeMatricula
         */
        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        /**
         * Verifica se o objeto principal "Matricula" existe.
         */
        if (!isset($objPHP->matricula)) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "O campo 'matricula' é obrigatório!"
                ]
            );
        }

        /**
         * Armazena os dados internos do funcionário
         * para facilitar leitura do código.
         */
        $Matricula = $objPHP->matricula;

        
        /**
         * Valida vínculo com turma.
         */
        if (
            !isset($Matricula->turma) ||
            !isset($Matricula->turma->tur_id) ||
            !is_int($Matricula->turma->tur_id) ||
            $Matricula->turma->tur_id <= 0
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" =>
                        "O campo 'tur_id' deve ser um número inteiro positivo"
                ]
            );
        }

        if (
            !isset($Matricula->aluno) ||
            !isset($Matricula->aluno->alu_id) ||
            !is_int($Matricula->aluno->alu_id) ||
            $Matricula->aluno->alu_id <= 0
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" =>
                        "O campo 'alu_id' deve ser um número inteiro positivo"
                ]
            );
        }

        /**
         * Se chegou aqui, está válido.
         */
        return $handler->handle($request);
    }
}