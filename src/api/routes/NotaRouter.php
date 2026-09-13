<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\NotaController;
use Api\Middlewares\Nota\ValidateNotaBody;
use Api\Middlewares\Nota\ValidateNotaId;

/**
 * Classe responsável por registrar as rotas do recurso Nota.
 *
 * Endpoints disponíveis:
 * - POST   /Notas
 * - PUT    /Not{matr}
 * - DELETE /Not{matr}
 * - GET    /Notas
 * - GET    /Notas/count
 * - GET    /Not{matr}
 */
class NotaRouter
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
     * @var NotaController
     */
    private NotaController $controller;

    /**
     * Recebe as dependências necessárias.
     *
     * @param App $app Aplicação Slim.
     * @param NotaController $controller Controller de funcionário.
     */
    public function __construct(App $app, NotaController $controller)
    {
        $this->app = $app;
        $this->controller = $controller;
    }

    /**
     * Registra todas as rotas relacionadas ao recurso Nota.
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
         * POST /Notas
         * =====================================================
         * Cria um novo funcionário.
         *
         * Body:
         * {
         *  "nota": {
         *     "fre": 85.5,
         *     "nota": 9,
         *  {matr": 1
         *  }
         * }    
         * Ordem de execução:
         * 1. ValidateNotaBody
         * 2. createController
         */
        $this->app->post(
            '/notas',
            [$this->controller, 'createController']
        )
        ->add(ValidateNotaBody::class);

        /**
         * =====================================================
         * PUT /Not{matr}
         * =====================================================
         * Atualiza um funcionário existente.
         *
         * Ordem de execução:
         * 1. ValidateNotaId
         * 2. ValidateNotaBody
         * 3. updateController
         */
        $this->app->put(
            '/notas/{fre_id}',
            [$this->controller, 'updateController']
        )
        ->add(ValidateNotaBody::class)
        ->add(ValidateNotaId::class);

        /**
         * =====================================================
         * DELETE /Notas{fre_id}
         * =====================================================
         * Remove um funcionário pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateNotaId
         * 2. deleteController
         */
        $this->app->delete(
            '/notas/{fre_id}',
            [$this->controller, 'deleteController']
        )
        ->add(ValidateNotaId::class);

        /**
         * =====================================================
         * GET /Notas
         * =====================================================
         * Lista todos os funcionários.
         *
         * Ordem de execução:
         * 1. findAllController
         */
    
        $this->app->get(
            '/notas',
            [$this->controller, 'findAllController']
        );

        
        $this->app->get(
            '/notas/count',
            [$this->controller, 'countController']
        );

        /**
         * =====================================================
         * GET /Not{matr}
         * =====================================================
         * Busca um funcionário pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateNotaId
         * 2. findByIdController
         */
        $this->app->get(
            '/notas/matricula/{matr_id}',
            [$this->controller, 'findByMatriculaController']
        );
         $this->app->get(
            '/notas/{fre_id}',
            [$this->controller, 'findByFreIdController']
        )
        ->add(ValidateNotaId::class);
    }
}