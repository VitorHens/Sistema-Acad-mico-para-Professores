<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\DisciplinaController;
use Api\Middlewares\Disciplina\ValidateDisciplinaBody;
use Api\Middlewares\Disciplina\ValidateDisciplinaId;

/**
 * Classe responsável por registrar as rotas do recurso Disciplina.
 *
 * Endpoints disponíveis:
 * - POST   /disciplinas
 * - GET    /disciplinas
 * - GET    /disciplinas/count
 * - GET    /disciplinas/{dis_id}
 * - PUT    /disciplinas/{dis_id}
 * - DELETE /disciplinas/{dis_id}
 */
class DisciplinaRouter
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
     * Registra todas as rotas relacionadas ao recurso Disciplina.
     *
     * Estrutura esperada do JSON:
     *
     * {
     *   "Disciplina": {
     *     "nomeDisciplina": "teste"
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
        /**
         * =========================================================
         * POST /disciplinas
         * =========================================================
         * Cria um novo Disciplina.
         *
         * Body:
         * {
         *   "Disciplina": {
         *     "nomeDisciplina": "teste"
         *   }
         * }
         *
         * Ordem de execução:
         * 1. ValidateDisciplinaBody
         * 2. DisciplinaController::createController
         */
        $this->app->post(
            '/disciplinas',
            [DisciplinaController::class, 'createController']
        )
            ->add(ValidateDisciplinaBody::class);

        /**
         * =========================================================
         * GET /disciplinas
         * =========================================================
         * Lista todos os disciplinas.
         *
         * Ordem de execução:
         * 1. DisciplinaController::findAllController
         */
        $this->app->get(
            '/disciplinas',
            [DisciplinaController::class, 'findAllController']
        );

        /**
         * =========================================================
         * GET /disciplinas/count
         * =========================================================
         * Retorna a quantidade total de disciplinas.
         *
         * Ordem de execução:
         * 1. DisciplinaController::countController
         */
        $this->app->get(
            '/disciplinas/count',
            [DisciplinaController::class, 'countController']
        );

        /**
         * =========================================================
         * GET /disciplinas/{dis_id}
         * =========================================================
         * Busca um Disciplina pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateDisciplinaId
         * 2. DisciplinaController::findByIdController
         */
        $this->app->get(
            '/disciplinas/{dis_id}',
            [DisciplinaController::class, 'findByIdController']
        )
            ->add(ValidateDisciplinaId::class);

        /**
         * =========================================================
         * PUT /disciplinas/{dis_id}
         * =========================================================
         * Atualiza um Disciplina existente.
         *
         * Body:
         * {
         *   "Disciplina": {
         *     "nomeDisciplina": "teste"
         *   }
         * }
         *
         * Ordem de execução:
         * 1. ValidateDisciplinaId
         * 2. ValidateDisciplinaBody
         * 3. DisciplinaController::updateController
         */
        $this->app->put(
            '/disciplinas/{dis_id}',
            [DisciplinaController::class, 'updateController']
        )
            ->add(ValidateDisciplinaBody::class)
            ->add(ValidateDisciplinaId::class);

        /**
         * =========================================================
         * DELETE /disciplinas/{dis_id}
         * =========================================================
         * Remove um Disciplina pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateDisciplinaId
         * 2. DisciplinaController::deleteController
         */
        $this->app->delete(
            '/disciplinas/{dis_id}',
            [DisciplinaController::class, 'deleteController']
        )
            ->add(ValidateDisciplinaId::class);
    }
}