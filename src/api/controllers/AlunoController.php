<?php

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\AlunoService;

/**
 * Classe AlunoController
 *
 * Responsável pelos endpoints REST da entidade Aluno.
 *
 * PADRÃO:
 * - Assinaturas em uma linha
 * - JSON convertido para stdClass
 * - Controller delega regras para Service
 */
class AlunoController
{
    /**
     * Serviço da entidade Aluno.
     *
     * @var AlunoService
     */
    private AlunoService $AlunoService;

    /**
     * Injeção de dependência.
     *
     * @param AlunoService $AlunoServiceDependency
     */
    public function __construct(AlunoService $AlunoServiceDependency)
    {
        error_log("⬆️ AlunoController::__construct()");
        $this->AlunoService = $AlunoServiceDependency;
    }

    /**
     * Cria novo Aluno.
     *
     * Endpoint:
     * POST /api/v1/Alunos
     *
     * JSON esperado:
     * {
     *   "Aluno": {
     *      "alu_nome": "Administrador"
     *   }
     * }
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 AlunoController::createController()");

        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        $novoAluno = $this->AlunoService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'Alunos' => [
                    [
                        'alu_id' => $novoAluno->getalu_id(),
                        'alu_nome' => $novoAluno->getalu_nome()
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    /**
     * Lista todos os Alunos.
     *
     * Endpoint:
     * GET /api/v1/Alunos
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function findAllController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 AlunoController::findAllController()");

        $Alunos = $this->AlunoService->findAllService();

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'alunos' => $Alunos
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Busca Aluno por ID.
     *
     * Endpoint:
     * GET /api/v1/Alunos/{alu_id}
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 AlunoController::findByIdController()");

        $alu_id = (int) $args['alu_id'];
        $Aluno = $this->AlunoService->findByIdService($alu_id);

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'alunos' => $Aluno
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Atualiza Aluno.
     *
     * Endpoint:
     * PUT /api/v1/Alunos/{alu_id}
     *
     * JSON esperado:
     * {
     *   "Aluno": {
     *      "alu_nome": "Novo Nome"
     *   }
     * }
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 AlunoController::updateController()");

        $alu_id = (int) $args['alu_id'];

        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        $alu_nome = $objPHP->aluno->alu_nome;

        $this->AlunoService->updateService($alu_id, $alu_nome);

        $resposta = [
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'alunos' => [
                    [
                        'alu_id' => $alu_id,
                        'alu_nome' => $alu_nome
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Exclui Aluno.
     *
     * Endpoint:
     * DELETE /api/v1/Alunos/{alu_id}
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 AlunoController::deleteController()");

        $alu_id = (int) $args['alu_id'];

        $this->AlunoService->deleteService($alu_id);

        $resposta = [
            'success' => true,
            'message' => 'Excluído com sucesso',
            'data' => [
                'alunos' => [
                    [
                        'alu_id' => $alu_id
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Conta total de Alunos.
     *
     * Endpoint:
     * GET /api/v1/Alunos/count
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 AlunoController::countController()");

        $total = $this->AlunoService->countService();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'count' => $total
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}