<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\ProfessorController;
use Api\Middlewares\Professor\ValidateProfessorBody;
use Api\Middlewares\Professor\ValidateProfessorId;
use Api\Middlewares\Professor\ValidateProfessorLoginBody;
use Api\Middlewares\Professor\ValidateProfessorToken;


/**
 * Classe responsável por registrar as rotas do recurso Professor.
 *
 * Endpoints disponíveis:
 * - POST   /professores
 * - GET    /professores
 * - GET    /professores/count
 * - GET    /professores/{prof_id}
 * - PUT    /professores/{prof_id}
 * - DELETE /professores/{prof_id}
 */
class ProfessorRouter
{
    /**
     * Instância da aplicação Slim.
     *
     * @var App
     */
    private App $app;

    /**
     * Recebe a instância principal da aplicação.
     *
     * @param App $app Aplicação Slim.
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Registra todas as rotas relacionadas ao recurso Professor.
     *
     * Estrutura esperada do JSON:
     *
     * {
     *   "Professor": {
     *     "nomeProfessor": "teste"
     *   }
     * }
     *
     * IMPORTANTE:
     * No Slim Framework, os middlewares executam em ordem inversa
     * à ordem em que são adicionados com ->add().
     *
     * O último middleware adicionado executa primeiro.
     *
     * @return void
     */
    public function setupRoutes(): void
    {

          $this->app->post(
            '/professores/login',
            [ProfessorController::class, 'loginController']
        )
            ->add(ValidateProfessorLoginBody::class);



        /**
         * =========================================================
         * POST /professores
         * =========================================================
         * Cria um novo Professor.
         *
         * Body:
         * {
         *   "Professor": {
         *     "nomeProfessor": "teste"
         *   }
         * }
         *
         * Ordem de execução:
         * 1. ValidateProfessorBody
         * 2. ProfessorController::createController
         */
        $this->app->post(
            '/professores',
            [ProfessorController::class, 'createController']
        )
            ->add(ValidateProfessorBody::class)
            ->add(ValidateProfessorToken::class);

        /**
         * =========================================================
         * GET /professores
         * =========================================================
         * Lista todos os professores.
         *
         * Ordem de execução:
         * 1. ProfessorController::findAllController
         */
        $this->app->get(
            '/professores',
            [ProfessorController::class, 'findAllController']
        )->add(ValidateProfessorToken::class);

        /**
         * =========================================================
         * GET /professores/count
         * =========================================================
         * Retorna a quantidade total de professores.
         *
         * Ordem de execução:
         * 1. ProfessorController::countController
         */
        $this->app->get(
            '/professores/count',
            [ProfessorController::class, 'countController']
        )->add(ValidateProfessorToken::class);

        /**
         * =========================================================
         * GET /professores/{prof_id}
         * =========================================================
         * Busca um Professor pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateProfessorId
         * 2. ProfessorController::findByIdController
         */
        $this->app->get(
            '/professores/{prof_id}',
            [ProfessorController::class, 'findByIdController']
        )
            ->add(ValidateProfessorId::class)
            ->add(ValidateProfessorToken::class);

        /**
         * =========================================================
         * PUT /professores/{prof_id}
         * =========================================================
         * Atualiza um Professor existente.
         *
         * Body:
         * {
         *   "Professor": {
         *     "nomeProfessor": "teste"
         *   }
         * }
         *
         * Ordem de execução:
         * 1. ValidateProfessorId
         * 2. ValidateProfessorBody
         * 3. ProfessorController::updateController
         */
        $this->app->put(
            '/professores/{prof_id}',
            [ProfessorController::class, 'updateController']
        )
            ->add(ValidateProfessorBody::class)
            ->add(ValidateProfessorId::class)
            ->add(ValidateProfessorToken::class);

        /**
         * =========================================================
         * DELETE /professores/{prof_id}
         * =========================================================
         * Remove um Professor pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateProfessorId
         * 2. ProfessorController::deleteController
         */
        $this->app->delete(
            '/professores/{prof_id}',
            [ProfessorController::class, 'deleteController']
        )
            ->add(ValidateProfessorId::class)   
            ->add(ValidateProfessorToken::class);
    }
}