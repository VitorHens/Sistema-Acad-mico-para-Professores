<?php

namespace Api\Controllers;

// Importações das classes necessárias
use Psr\Http\Message\ResponseInterface as Response; // Interface para respostas HTTP PSR-7
use Psr\Http\Message\ServerRequestInterface as Request; // Interface para requisições HTTP PSR-7
use Api\Services\TurmaService; // Serviço de negócio para operações com funcionários

/**
 * Classe TurmaController
 *
 * Responsável por controlar os endpoints da API REST para a entidade Turma.
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
 * - Acesso via objeto: $objPHP->Turma->email
 * - Assinaturas em uma única linha
 */
class TurmaController
{
    /**
     * Serviço responsável pelas regras de negócio.
     *
     * @var TurmaService
     */
    private TurmaService $TurmaService;

    /**
     * Construtor com injeção de dependência.
     *
     * @param TurmaService $TurmaService
     */
    public function __construct(TurmaService $TurmaService)
    {
        error_log("⬆️ TurmaController::__construct()");
        $this->TurmaService = $TurmaService;
    }

    /**
     * Cria novo funcionário.
     *
     * Endpoint: POST /api/v1/Turmas
     *
     * @return Response
     */
    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 TurmaController::createController()");

        // Lê JSON bruto e converte para stdClass
        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        // Service recebe objeto Turma
        $resultado = $this->TurmaService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'Turmas' => [
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
     * Endpoint: GET /api/v1/Turmas
     *
     * @return Response
     */
    public function findAllController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 TurmaController::findAllController()");

        $lista = $this->TurmaService->findAll();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'Turmas' => $lista
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
     * Endpoint: GET /api/v1/Turmas/{tur_id}
     *
     * @return Response
     */
    public function findByidController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 TurmaController::findByidController()");

        $id = (int) $args['tur_id'];

        $Turma = $this->TurmaService->findByIdService($id);

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'Turmas' => [
                    $Turma
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
     * Endpoint: GET /api/v1/Turmas/count
     *
     * @return Response
     */
    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 TurmaController::countController()");

        $qtd = $this->TurmaService->countService();

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
     * Endpoint: PUT /api/v1/Turmas/{tur_id}
     *
     * @return Response
     */
    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 TurmaController::updateController()");

        $id = (int) $args['tur_id'];

        // Lê JSON bruto e converte para stdClass
        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        /**
         * CORREÇÃO:
         * Seu Service ainda espera ARRAY no segundo parâmetro:
         * updateService(int $tur_id, array $requestBody)
         *
         * Então convertemos stdClass para array associativo.
         */
        $arrayPHP = json_decode($body, true);

        // Executa de fato a atualização no banco
        $this->TurmaService->updateService($id, $arrayPHP);

        // Busca o registro já atualizado para devolver na resposta
        $resultado = $this->TurmaService->findByIdService($id);

        $resposta = [
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'Turmas' => [
                    $resultado
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
     * Endpoint: DELETE /api/v1/Turmas/{tur_id}
     *
     * @return Response
     */
    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 TurmaController::deleteController()");

        $id = (int) $args['tur_id'];

        $excluiu = $this->TurmaService->deleteService($id);

        $status = $excluiu ? 200 : 404;
        $mensagem = $excluiu
            ? 'Excluído com sucesso'
            : 'Turma não encontrada';

        $resposta = [
            'success' => $excluiu,
            'message' => $mensagem,
            'data' => [
                'tur_id' => $id
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}