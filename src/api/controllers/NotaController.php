<?php

namespace Api\Controllers;

// Importações das classes necessárias
use Psr\Http\Message\ResponseInterface as Response; // Interface para respostas HTTP PSR-7
use Psr\Http\Message\ServerRequestInterface as Request; // Interface para requisições HTTP PSR-7
use Api\Services\NotaService; // Serviço de negócio para operações com funcionários

/**
 * Classe NotaController
 *
 * Responsável por controlar os endpoints da API REST para a entidade Nota.
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
 * - Acesso via objeto: $objPHP->Nota->email
 * - Assinaturas em uma única linha
 */
class NotaController
{
    /**
     * Serviço responsável pelas regras de negócio.
     *
     * @var NotaService
     */
    private NotaService $NotaService;

    /**
     * Construtor com injeção de dependência.
     *
     * @param NotaService $NotaService
     */
    public function __construct(NotaService $NotaService)
    {
        error_log("⬆️ NotaController::__construct()");
        $this->NotaService = $NotaService;
    }

    /**
     * Cria novo funcionário.
     *
     * Endpoint: POST /api/v1/Notas
     *
     * @return Response
     */
    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 NotaController::createController()");

        // Lê JSON bruto e converte para stdClass
        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        // Service recebe objeto Nota
        $resultado = $this->NotaService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'Notas' => [
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
     * Endpoint: GET /api/v1/Notas
     *
     * @return Response
     */
    public function findAllController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 NotaController::findAllController()");

        $lista = $this->NotaService->findAll();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'Notas' => $lista
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
     * Endpoint: GET /api/v1/Notas/{matr_id}
     *
     * @return Response
     */
    public function findByFreidController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 NotaController::findByidController()");

        $id = (int) $args['fre_id'];

        $Nota = $this->NotaService->findByIdService($id);

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'Notas' => [
                    $Nota
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
    public function findByMatriculaController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['matr_id'];
        $Notas = $this->NotaService->findByMatriculaService($id);
    
        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => ['Notas' => $Notas]
        ];
    
        $response->getBody()->write(json_encode($resposta));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    /**
     * Retorna contagem total.
     *
     * Endpoint: GET /api/v1/Notas/count
     *
     * @return Response
     */
    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 NotaController::countController()");

        $qtd = $this->NotaService->countService();

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
     * Endpoint: PUT /api/v1/Notas/{matr_id}
     *
     * @return Response
     */
    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 NotaController::updateController()");

        $id = (int) $args['fre_id'];

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
        $this->NotaService->updateService($id, $arrayPHP);

        $resultado = $this->NotaService->findByIdService($id);

        $resposta = [
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'Notas' => [
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
     * Endpoint: DELETE /api/v1/Notas/{matr_id}
     *
     * @return Response
     */
    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 NotaController::deleteController()");

        $id = (int) $args['fre_id'];

        $excluiu = $this->NotaService->deleteService($id);

        $status = $excluiu ? 200 : 404;
        $mensagem = $excluiu
            ? 'Excluído com sucesso'
            : 'Nota não encontrada';

        $resposta = [
            'success' => $excluiu,
            'message' => $mensagem,
            'data' => [
                'fre_id' => $id
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}