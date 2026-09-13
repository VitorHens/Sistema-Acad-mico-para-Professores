<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\AlunoController;
use Api\Middlewares\Aluno\ValidateAlunoBody;
use Api\Middlewares\Aluno\ValidateAlunoId;

/**
 * Classe responsável por registrar as rotas do recurso Aluno.
 *
 * Endpoints disponíveis:
 * - POST   /alunos
 * - GET    /alunos
 * - GET    /alunos/count
 * - GET    /alunos/{alu_id}
 * - PUT    /alunos/{alu_id}
 * - DELETE /alunos/{alu_id}
 */
class AlunoRouter
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
     * Registra todas as rotas relacionadas ao recurso Aluno.
     *
     * Estrutura esperada do JSON:
     *
     * {
     *   "Aluno": {
     *     "nomeAluno": "teste"
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
         * POST /alunos
         * =========================================================
         * Cria um novo Aluno.
         *
         * Body:
         * {
         *   "Aluno": {
         *     "nomeAluno": "teste"
         *   }
         * }
         *
         * Ordem de execução:
         * 1. ValidateAlunoBody
         * 2. AlunoController::createController
         */
        $this->app->post(
            '/alunos',
            [AlunoController::class, 'createController']
        )
            ->add(ValidateAlunoBody::class);

        /**
         * =========================================================
         * GET /alunos
         * =========================================================
         * Lista todos os alunos.
         *
         * Ordem de execução:
         * 1. AlunoController::findAllController
         */
        $this->app->get(
            '/alunos',
            [AlunoController::class, 'findAllController']
        );

        /**
         * =========================================================
         * GET /alunos/count
         * =========================================================
         * Retorna a quantidade total de alunos.
         *
         * Ordem de execução:
         * 1. AlunoController::countController
         */
        $this->app->get(
            '/alunos/count',
            [AlunoController::class, 'countController']
        );

        /**
         * =========================================================
         * GET /alunos/{alu_id}
         * =========================================================
         * Busca um Aluno pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateAlunoId
         * 2. AlunoController::findByIdController
         */
        $this->app->get(
            '/alunos/{alu_id}',
            [AlunoController::class, 'findByIdController']
        )
            ->add(ValidateAlunoId::class);

        /**
         * =========================================================
         * PUT /alunos/{alu_id}
         * =========================================================
         * Atualiza um Aluno existente.
         *
         * Body:
         * {
         *   "Aluno": {
         *     "nomeAluno": "teste"
         *   }
         * }
         *
         * Ordem de execução:
         * 1. ValidateAlunoId
         * 2. ValidateAlunoBody
         * 3. AlunoController::updateController
         */
        $this->app->put(
            '/alunos/{alu_id}',
            [AlunoController::class, 'updateController']
        )
            ->add(ValidateAlunoBody::class)
            ->add(ValidateAlunoId::class);

        /**
         * =========================================================
         * DELETE /alunos/{alu_id}
         * =========================================================
         * Remove um Aluno pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateAlunoId
         * 2. AlunoController::deleteController
         */
        $this->app->delete(
            '/alunos/{alu_id}',
            [AlunoController::class, 'deleteController']
        )
            ->add(ValidateAlunoId::class);
    }
}