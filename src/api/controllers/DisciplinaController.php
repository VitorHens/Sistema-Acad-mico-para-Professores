<?php

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\DisciplinaService;

/**
 * Classe DisciplinaController
 *
 * Responsável pelos endpoints REST da entidade Disciplina.
 *
 * PADRÃO:
 * - Assinaturas em uma linha
 * - JSON convertido para stdClass
 * - Controller delega regras para Service
 */
class DisciplinaController
{
    /**
     * Serviço da entidade Disciplina.
     *
     * @var DisciplinaService
     */
    private DisciplinaService $DisciplinaService;

    /**
     * Injeção de dependência.
     *
     * @param DisciplinaService $DisciplinaServiceDependency
     */
    public function __construct(DisciplinaService $DisciplinaServiceDependency)
    {
        error_log("⬆️ DisciplinaController::__construct()");
        $this->DisciplinaService = $DisciplinaServiceDependency;
    }

    /**
     * Cria novo Disciplina.
     *
     * Endpoint:
     * POST /api/v1/Disciplinas
     *
     * JSON esperado:
     * {
     *   "Disciplina": {
     *      "dis_nome": "Administrador"
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
        error_log("🔵 DisciplinaController::createController()");

        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        $novoDisciplina = $this->DisciplinaService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'disciplinas' => [
                    [
                        'dis_id' => $novoDisciplina->getdis_id(),
                        'dis_nome' => $novoDisciplina->getdis_nome()
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
     * Lista todos os Disciplinas.
     *
     * Endpoint:
     * GET /api/v1/Disciplinas
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function findAllController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 DisciplinaController::findAllController()");

        $Disciplinas = $this->DisciplinaService->findAllService();

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'disciplinas' => $Disciplinas
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Busca Disciplina por ID.
     *
     * Endpoint:
     * GET /api/v1/Disciplinas/{dis_id}
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 DisciplinaController::findByIdController()");

        $dis_id = (int) $args['dis_id'];
        $Disciplina = $this->DisciplinaService->findByIdService($dis_id);

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'disciplinas' => $Disciplina
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Atualiza Disciplina.
     *
     * Endpoint:
     * PUT /api/v1/Disciplinas/{dis_id}
     *
     * JSON esperado:
     * {
     *   "Disciplina": {
     *      "dis_nome": "Novo Nome"
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
        error_log("🔵 DisciplinaController::updateController()");

        $dis_id = (int) $args['dis_id'];

        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        $dis_nome = $objPHP->disciplina->dis_nome;

        $this->DisciplinaService->updateService($dis_id, $dis_nome);

        $resposta = [
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'disciplinas' => [
                    [
                        'dis_id' => $dis_id,
                        'dis_nome' => $dis_nome
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
     * Exclui Disciplina.
     *
     * Endpoint:
     * DELETE /api/v1/Disciplinas/{dis_id}
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 DisciplinaController::deleteController()");

        $dis_id = (int) $args['dis_id'];

        $this->DisciplinaService->deleteService($dis_id);

        $resposta = [
            'success' => true,
            'message' => 'Excluído com sucesso',
            'data' => [
                'disciplinas' => [
                    [
                        'dis_id' => $dis_id
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
     * Conta total de Disciplinas.
     *
     * Endpoint:
     * GET /api/v1/Disciplinas/count
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 DisciplinaController::countController()");

        $total = $this->DisciplinaService->countService();

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