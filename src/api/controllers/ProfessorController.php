<?php

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\ProfessorService;

/**
 * Classe ProfessorController
 *
 * Responsável pelos endpoints REST da entidade Professor.
 *
 * PADRÃO:
 * - Assinaturas em uma linha
 * - JSON convertido para stdClass
 * - Controller delega regras para Service
 */
class ProfessorController
{
    /**
     * Serviço da entidade Professor.
     *
     * @var ProfessorService
     */
    private ProfessorService $ProfessorService;

    /**
     * Injeção de dependência.
     *
     * @param ProfessorService $ProfessoreserviceDependency
     */
    public function __construct(ProfessorService $ProfessorServiceDependency)
    {
        error_log("⬆️ ProfessorController::__construct()");
        $this->ProfessorService = $ProfessorServiceDependency;
    }
     /**
     * Realiza autenticação do funcionário.
     *
     * Endpoint:
     * POST /api/v1/professor/login
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function loginController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 ProfessorController::loginController()");

        $body = $request->getBody()->getContents();

        $objPHP = json_decode($body, true);

        $resultado = $this->ProfessorService->loginService($objPHP['professor']);

        $professor = $resultado['professor'];

        $token = $resultado['token'];

        $resposta = [
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'data' => [
                'professor' => [
                    'prof_id' => $professor->getprof_id(),
                    'prof_nome' => $professor->getprof_nome()
                ],
                'token' => $token
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Cria novo Professor.
     *
     * Endpoint:
     * POST /api/v1/Professores
     *
     * JSON esperado:
     * {
     *   "Professor": {
     *      "prof_nome": "Administrador"
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
        error_log("🔵 ProfessorController::createController()");

        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        $novoProfessor = $this->ProfessorService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'Professores' => [
                    [
                        'prof_id' => $novoProfessor->getprof_id(),
                        'prof_nome' => $novoProfessor->getprof_nome()
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
     * Lista todos os Professores.
     *
     * Endpoint:
     * GET /api/v1/Professores
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function findAllController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 ProfessorController::findAllController()");

        $Professores = $this->ProfessorService->findAllService();

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'Professores' => $Professores
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Busca Professor por ID.
     *
     * Endpoint:
     * GET /api/v1/Professores/{prof_id}
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 ProfessorController::findByIdController()");

        $prof_id = (int) $args['prof_id'];
        $Professor = $this->ProfessorService->findByIdService($prof_id);

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'Professores' => $Professor
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Atualiza Professor.
     *
     * Endpoint:
     * PUT /api/v1/Professores/{prof_id}
     *
     * JSON esperado:
     * {
     *   "Professor": {
     *      "prof_nome": "Novo Nome"
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
        error_log("🔵 ProfessorController::updateController()");

        $prof_id = (int) $args['prof_id'];

        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        $prof_nome = $objPHP->professor->prof_nome;

        $this->ProfessorService->updateService($prof_id, $prof_nome);

        $resposta = [
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'Professores' => [
                    [
                        'prof_id' => $prof_id,
                        'prof_nome' => $prof_nome
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
     * Exclui Professor.
     *
     * Endpoint:
     * DELETE /api/v1/Professores/{prof_id}
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 ProfessorController::deleteController()");

        $prof_id = (int) $args['prof_id'];

        $this->ProfessorService->deleteService($prof_id);

        $resposta = [
            'success' => true,
            'message' => 'Excluído com sucesso',
            'data' => [
                'Professores' => [
                    [
                        'prof_id' => $prof_id
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
     * Conta total de Professores.
     *
     * Endpoint:
     * GET /api/v1/Professores/count
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 ProfessorController::countController()");

        $total = $this->ProfessorService->countService();

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