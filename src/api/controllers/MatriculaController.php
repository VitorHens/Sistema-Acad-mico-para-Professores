<?php

namespace Api\Controllers;

// Importações das classes necessárias
use Psr\Http\Message\ResponseInterface as Response; // Interface para respostas HTTP PSR-7
use Psr\Http\Message\ServerRequestInterface as Request; // Interface para requisições HTTP PSR-7
use Api\Services\MatriculaService; // Serviço de negócio para operações com funcionários

/**
 * Classe MatriculaController
 *
 * Responsável por controlar os endpoints da API REST para a entidade Matricula.
 * Implementa o padrão Controller da arquitetura MVC, atuando como intermediário
 * entre as rotas (Router) e a camada de serviço (Service).
 *
 * FLUXO DE UMA REQUISIÇÃO:
 * 1. Rota chama o método do controller
 * 2. Controller extrai dados da requisição
 * 3. Controller chama métodos do Service
 * 4. Controller formata resposta padronizada
 * 5. Retorna Response com JSON e status HTTP
 *
 * PADRÃO ADOTADO:
 * - Uso de stdClass com json_decode($body)
 * - Acesso via objeto: $objPHP->Matricula->email
 * - Assinaturas em uma única linha
 */
class MatriculaController
{
    /**
     * Serviço responsável pelas regras de negócio.
     *
     * @var MatriculaService
     */
    private MatriculaService $MatriculaService;

    /**
     * Construtor com injeção de dependência.
     *
     * @param MatriculaService $MatriculaService
     */
    public function __construct(MatriculaService $MatriculaService)
    {
        error_log("⬆️ MatriculaController::__construct()");
        $this->MatriculaService = $MatriculaService;
    }

    /**
     * Cria novo funcionário.
     *
     * Endpoint: POST /api/v1/Matriculas
     *
     * @return Response
     */
    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 MatriculaController::createController()");

        // Lê JSON bruto e converte para stdClass
        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        // Service recebe objeto Matricula
        $resultado = $this->MatriculaService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'Matriculas' => [
                    $resultado
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    /**
     * Lista todos os funcionários.
     *
     * Endpoint: GET /api/v1/Matriculas
     *
     * @return Response
     */
    public function findAllController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 MatriculaController::findAllController()");

        $lista = $this->MatriculaService->findAll();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'Matriculas' => $lista
            ]
        ];

        $response->getBody()->write(
            json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Busca funcionário por ID.
     *
     * Endpoint: GET /api/v1/Matriculas/{matr_id}
     *
     * @return Response
     */
    public function findByidController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 MatriculaController::findByidController()");

        $id = (int) $args['matr_id'];

        $Matricula = $this->MatriculaService->findByIdService($id);

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'Matriculas' => [
                    $Matricula
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Retorna contagem total.
     *
     * Endpoint: GET /api/v1/Matriculas/count
     *
     * @return Response
     */
    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 MatriculaController::countController()");

        $qtd = $this->MatriculaService->countService();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'count' => $qtd
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Atualiza funcionário existente.
     *
     * Endpoint: PUT /api/v1/Matriculas/{matr_id}
     *
     * @return Response
     */
    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 MatriculaController::updateController()");

        $id = (int) $args['matr_id'];

        // Lê JSON bruto e converte para stdClass
        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        /**
         * CORREÇÃO:
         * Seu Service ainda espera ARRAY no segundo parâmetro:
         * updateService(int $matr_id, array $requestBody)
         *
         * Então convertemos stdClass para array associativo.
         */
        $arrayPHP = json_decode($body, true);

        $resultado = $this->MatriculaService->updateService($id, $arrayPHP);

        $matriculaAtualizada = $this->MatriculaService->findByIdService($id);
         
        $resposta = [
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'Matriculas' => [
                    $matriculaAtualizada
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Remove funcionário.
     *
     * Endpoint: DELETE /api/v1/Matriculas/{matr_id}
     *
     * @return Response
     */
    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 MatriculaController::deleteController()");

        $id = (int) $args['matr_id'];

        $excluiu = $this->MatriculaService->deleteService($id);

        $status = $excluiu ? 200 : 404;
        $mensagem = $excluiu
            ? 'Excluído com sucesso'
            : 'Matricula não encontrada';

        $resposta = [
            'success' => $excluiu,
            'message' => $mensagem,
            'data' => [
                'matr_id' => $id
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}