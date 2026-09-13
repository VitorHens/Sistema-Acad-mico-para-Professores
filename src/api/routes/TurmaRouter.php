<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\TurmaController;
use Api\Middlewares\Turma\ValidateTurmaBody;
use Api\Middlewares\Turma\ValidateTurmaId;

/**
 * Classe responsável por registrar as rotas do recurso Turma.
 *
 * Endpoints disponíveis:
 * - POST   /turmas
 * - PUT    /turmas/{tur_id}
 * - DELETE /turmas/{tur_id}
 * - GET    /turmas
 * - GET    /turmas/count
 * - GET    /turmas/{tur_id}
 */
class TurmaRouter
{
    /**
     * Instância principal da aplicação Slim.
     *
     * @var App
     */
    private App $app;

    /**
     * Controller responsável pelas regras de negócio.
     *
     * @var TurmaController
     */
    private TurmaController $controller;

    /**
     * Recebe as dependências necessárias.
     *
     * @param App $app Aplicação Slim.
     * @param TurmaController $controller Controller de funcionário.
     */
    public function __construct(App $app, TurmaController $controller)
    {
        $this->app = $app;
        $this->controller = $controller;
    }

    /**
     * Registra todas as rotas relacionadas ao recurso turma.
     *
     * IMPORTANTE:
     * No Slim Framework, os middlewares executam
     * na ordem inversa em que são adicionados.
     *
     * Exemplo:
     * ->add(A)->add(B)
     *
     * Ordem real:
     * 1. B
     * 2. A
     * 3. Controller
     *
     * @return void
     */
    public function setupRoutes(): void
    {
        /**
         * =====================================================
         * POST /turmas
         * =====================================================
         * Cria um novo funcionário.
         *
         * Body:
         * {
         *   "Turma": {
         *     "nomeTurma": "João",
         *     "email": "joao@email.com",
         *     "senha": "123456",
         *     "recebeValeTransporte": 1,
         *     "cargo": {
         *       "idCargo": 1
         *     }
         *   }
         * }
         *
         * Ordem de execução:
         * 1. ValidateTurmaBody
         * 2. createController
         */
        $this->app->post(
            '/turmas',
            [$this->controller, 'createController']
        )
        ->add(ValidateTurmaBody::class);

        /**
         * =====================================================
         * PUT /turmas/{tur_id}
         * =====================================================
         * Atualiza um funcionário existente.
         *
         * Ordem de execução:
         * 1. ValidateTurmaId
         * 2. ValidateTurmaBody
         * 3. updateController
         */
        $this->app->put(
            '/turmas/{tur_id}',
            [$this->controller, 'updateController']
        )
        ->add(ValidateTurmaBody::class)
        ->add(ValidateTurmaId::class);

        /**
         * =====================================================
         * DELETE /turmas/{tur_id}
         * =====================================================
         * Remove um funcionário pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateTurmaId
         * 2. deleteController
         */
        $this->app->delete(
            '/turmas/{tur_id}',
            [$this->controller, 'deleteController']
        )
        ->add(ValidateTurmaId::class);

        /**
         * =====================================================
         * GET /turmas
         * =====================================================
         * Lista todos os funcionários.
         *
         * Ordem de execução:
         * 1. findAllController
         */
        $this->app->get(
            '/turmas',
            [$this->controller, 'findAllController']
        );

        /**
         * =====================================================
         * GET /turmas/count
         * =====================================================
         * Retorna a quantidade total de funcionários.
         *
         * Ordem de execução:
         * 1. countController
         */
        $this->app->get(
            '/turmas/count',
            [$this->controller, 'countController']
        );

        /**
         * =====================================================
         * GET /turmas/{tur_id}
         * =====================================================
         * Busca um funcionário pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateTurmaId
         * 2. findByIdController
         */
        $this->app->get(
            '/turmas/{tur_id}',
            [$this->controller, 'findByIdController']
        )
        ->add(ValidateTurmaId::class);
    }
}