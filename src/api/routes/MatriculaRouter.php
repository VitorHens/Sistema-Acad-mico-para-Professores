<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\MatriculaController;
use Api\Middlewares\Matricula\ValidateMatriculaBody;
use Api\Middlewares\Matricula\ValidateMatriculaId;

/**
 * Classe responsável por registrar as rotas do recurso Matricula.
 *
 * Endpoints disponíveis:
 * - POST   /matriculas
 * - PUT    /matriculas/{matr_id}
 * - DELETE /matriculas/{matr_id}
 * - GET    /matriculas
 * - GET    /matriculas/count
 * - GET    /matriculas/{matr_id}
 */
class MatriculaRouter
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
     * @var MatriculaController
     */
    private MatriculaController $controller;

    /**
     * Recebe as dependências necessárias.
     *
     * @param App $app Aplicação Slim.
     * @param MatriculaController $controller Controller de funcionário.
     */
    public function __construct(App $app, MatriculaController $controller)
    {
        $this->app = $app;
        $this->controller = $controller;
    }

    /**
     * Registra todas as rotas relacionadas ao recurso Matricula.
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
         * POST /matriculas
         * =====================================================
         * Cria um novo funcionário.
         *
         * Body:
         * {
         *  "matricula": {
         *    "nomeMatricula": "João",
         *    "email": "joao@email.com",
         *    "senha": "123456",
         *    "recebeValeTransporte": 1,
         *    "turma": {
         *     "tur_id": 2
         *    },
         *    "aluno": {
         *      "alu_id" : 3
         *	  }
         *  }
         * }
         * Ordem de execução:
         * 1. ValidateMatriculaBody
         * 2. createController
         */
        $this->app->post(
            '/matriculas',
            [$this->controller, 'createController']
        )
        ->add(ValidateMatriculaBody::class);

        /**
         * =====================================================
         * PUT /matriculas/{matr_id}
         * =====================================================
         * Atualiza um funcionário existente.
         *
         * Ordem de execução:
         * 1. ValidateMatriculaId
         * 2. ValidateMatriculaBody
         * 3. updateController
         */
        $this->app->put(
            '/matriculas/{matr_id}',
            [$this->controller, 'updateController']
        )
        ->add(ValidateMatriculaBody::class)
        ->add(ValidateMatriculaId::class);

        /**
         * =====================================================
         * DELETE /matriculas/{matr_id}
         * =====================================================
         * Remove um funcionário pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateMatriculaId
         * 2. deleteController
         */
        $this->app->delete(
            '/matriculas/{matr_id}',
            [$this->controller, 'deleteController']
        )
        ->add(ValidateMatriculaId::class);

        /**
         * =====================================================
         * GET /matriculas
         * =====================================================
         * Lista todos os funcionários.
         *
         * Ordem de execução:
         * 1. findAllController
         */
        $this->app->get(
            '/matriculas',
            [$this->controller, 'findAllController']
        );

        /**
         * =====================================================
         * GET /matriculas/count
         * =====================================================
         * Retorna a quantidade total de funcionários.
         *
         * Ordem de execução:
         * 1. countController
         */
        $this->app->get(
            '/matriculas/count',
            [$this->controller, 'countController']
        );

        /**
         * =====================================================
         * GET /matriculas/{matr_id}
         * =====================================================
         * Busca um funcionário pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateMatriculaId
         * 2. findByIdController
         */
        $this->app->get(
            '/matriculas/{matr_id}',
            [$this->controller, 'findByIdController']
        )
        ->add(ValidateMatriculaId::class);
    }
}