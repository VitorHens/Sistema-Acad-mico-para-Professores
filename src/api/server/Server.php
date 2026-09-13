<?php

namespace Api\Server;

// Importações das classes necessárias
use Slim\App;                          // Aplicação Slim para gerenciar rotas e middlewares
use Psr\Http\Message\ServerRequestInterface;  // Interface para requisições HTTP
use Api\Http\ErrorResponse;              // Classe personalizada para respostas de erro
use Api\Routes\AlunoRouter;              // Router para rotas de Alunos
use Api\Routes\ProfessorRouter;        // Router para rotas de funcionários
use Api\Routes\DisciplinaRouter;              // Router para rotas de Alunos
use Api\Routes\TurmaRouter;        // Router para rotas de funcionários
use Api\Routes\MatriculaRouter;              // Router para rotas de Alunos
use Api\Routes\NotaRouter;        // Router para rotas de funcionários


/**
 * Classe Server - Responsável por configurar e executar o servidor HTTP
 * 
 * Esta classe orquestra toda a configuração da aplicação, incluindo:
 * - Middlewares (processamento de requisições/respostas)
 * - Rotas (endpoints da API)
 * - Tratamento de erros (captura e formatação de exceções)
 * 
 * Princípios aplicados:
 * - Inversão de Controle (IoC): As dependências são recebidas prontas (injeção)
 * - Single Responsibility: Cada método tem uma responsabilidade única
 * - DRY (Don't Repeat Yourself): Configurações centralizadas
 */
class Server
{
    /**
     * Instância da aplicação Slim
     * @var App
     */
    private App $app;

    /**
     * Router responsável pelas rotas de Alunos
     * @var AlunoRouter
     * @var ProfessorRouter
     * @var DisciplinaRouter
     * @var TurmaRouter
     * @var MatriculaRouter
     */
    private AlunoRouter $AlunoRouter;
    private ProfessorRouter $ProfessorRouter;
    private DisciplinaRouter $DisciplinaRouter;
    private TurmaRouter $TurmaRouter;
    private MatriculaRouter $MatriculaRouter;
    private NotaRouter $NotaRouter;

    /**
     * Construtor da classe Server
     * 
     * Recebe as dependências já instanciadas pelo Container de Injeção de Dependência.
     * Isso é chamado de "Injeção de Dependência via Construtor".
     * 
     * @param App $app Instância da aplicação Slim (injetada pelo Container)
     * @param AlunoRouter $AlunoRouter Router para rotas de Alunos (injetado pelo Container)
     * @param ProfessorRouter $AlunoRouter Router para rotas de Alunos (injetado pelo Container)
     * @param DisciplinaRouter $AlunoRouter Router para rotas de Alunos (injetado pelo Container)
     * @param TurmaRouter $AlunoRouter  
     * @param MatriculaRouter $AlunoRouter
     * @param NotaRouter $AlunoRouter

     * */
    public function __construct(
        App $app,
        AlunoRouter $AlunoRouter,
        ProfessorRouter $ProfessorRouter,
        DisciplinaRouter $DisciplinaRouter,
        TurmaRouter $TurmaRouter,
        MatriculaRouter $MatriculaRouter,
        NotaRouter $NotaRouter,

    ) {
        // Armazena as dependências recebidas nas propriedades privadas da classe
        $this->app = $app;
        $this->AlunoRouter = $AlunoRouter;
        $this->ProfessorRouter = $ProfessorRouter;
        $this->DisciplinaRouter = $DisciplinaRouter;
        $this->TurmaRouter = $TurmaRouter;
        $this->MatriculaRouter = $MatriculaRouter;
        $this->NotaRouter = $NotaRouter;

        // Configurações executadas automaticamente quando a classe é instanciada
        // A ordem importa: middlewares primeiro, depois rotas, por fim tratamento de erros
        $this->setupMiddlewares();      // Configura os middlewares da aplicação
        $this->setupRoutes();           // Configura as rotas da API
        $this->setupErrorHandling();    // Configura o tratamento de erros
    }

    /**
     * Configura os middlewares da aplicação
     * 
     * Middlewares são funções que executam antes ou depois das rotas,
     * permitindo processar requisições e respostas de forma global.
     * 
     * Funcionam como uma "camada de cebola": a requisição passa por eles
     * até chegar na rota, e a resposta passa por eles de volta.
     * 
     * @return void
     */
    private function setupMiddlewares(): void
    {
        // =============================================================
        // MIDDLEWARE 1: Body Parsing Middleware
        // =============================================================
        // Parser JSON/Form - Middleware que analisa o corpo das requisições
        // Converte automaticamente JSON e dados de formulário em arrays PHP
        // Exemplo: Se enviar {"nome": "João"} no body, vira $request->getParsedBody()['nome'] = 'João'
        // Isso é essencial para APIs REST que recebem dados em JSON
        $this->app->addBodyParsingMiddleware();

        // =============================================================
        // MIDDLEWARE 2: CORS Middleware
        // =============================================================
        // Middleware de CORS (Cross-Origin Resource Sharing)
        // Permite que a API seja acessada de diferentes origens (domínios)
        // Sem isso, um frontend rodando em http://localhost:3000 não conseguiria
        // acessar uma API em http://localhost:8000 (política de mesma origem)
        $this->app->add(function ($request, $handler) {
            // O $handler representa o próximo middleware ou a rota final
            // Primeiro, processa a requisição através da cadeia de middlewares
            $response = $handler->handle($request);

            // Depois de obter a resposta, adiciona os headers de CORS
            // Isso permite que o navegador do cliente aceite a resposta
            return $response
                // Access-Control-Allow-Origin: '*' permite qualquer domínio
                // Em produção, troque '*' pelo domínio específico do frontend (ex: 'http://meusite.com')
                ->withHeader('Access-Control-Allow-Origin', '*')

                // Define quais métodos HTTP são permitidos em requisições cross-origin
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')

                // Define quais headers podem ser enviados nas requisições
                // Content-Type: para especificar formato dos dados
                // Authorization: para enviar tokens de autenticação
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        });
    }

    /**
     * Configura todas as rotas da aplicação
     * 
     * As rotas definem como a API responde a diferentes URLs e métodos HTTP.
     * Cada URL corresponde a um "endpoint" da API.
     * 
     * @return void
     */
    private function setupRoutes(): void
    {
        // =============================================================
        // DELEGAÇÃO DE ROTAS PARA ROUTERS ESPECÍFICOS
        // =============================================================
        // 🔥 Delega a configuração das rotas para cada router específico
        // Isso mantém o código organizado (separação por responsabilidade)
        // Cada router é especialista em um recurso da API

        // Router de Alunos: configura todas as rotas que começam com /Alunos
        // Exemplos: GET /Alunos, POST /Alunos, GET /Alunos/{id}, PUT /Alunos/{id}, DELETE /Alunos/{id}
        $this->AlunoRouter->setupRoutes();  // Rotas para /Alunos/*
        $this->ProfessorRouter->setupRoutes();  // Rotas para /Alunos/*
        $this->DisciplinaRouter->setupRoutes();  // Rotas para /Alunos/*
        $this->TurmaRouter->setupRoutes();  // Rotas para /Alunos/*
        $this->MatriculaRouter->setupRoutes();  // Rotas para /Alunos/*
        $this->NotaRouter->setupRoutes();  // Rotas para /Alunos/*

        // =============================================================
        // ROTA RAIZ (REDIRECIONAMENTO)
        // =============================================================
        // Rota raiz - quando alguém acessa a URL base da API
        // Exemplo: http://localhost:8000/
        $this->app->get('/', function ($request, $response) {
            // Redireciona para a página de login
            // 302 é o código HTTP para "redirecionamento temporário" (Found)
            // O navegador automaticamente fará uma nova requisição para /login.html
            return $response
                ->withHeader('Location', '/login.html')  // Define o header de redirecionamento
                ->withStatus(302);                        // Define o status HTTP 302
        });

        // Nota: Em APIs REST puras, geralmente não se faz redirecionamentos.
        // Este é um caso específico para servir uma página HTML de login.
    }

    /**
     * Configura o tratamento global de erros da aplicação
     * 
     * Captura todas as exceções não tratadas e as converte
     * em respostas JSON padronizadas.
     * 
     * Isso garante que mesmo erros inesperados retornem respostas
     * estruturadas em vez de páginas de erro do PHP.
     * 
     * @return void
     */
    private function setupErrorHandling(): void
    {
        // =============================================================
        // CONFIGURAÇÃO DO MIDDLEWARE DE ERRO
        // =============================================================
        // Cria um middleware de erro com debug habilitado
        // Parâmetros: (displayErrorDetails, logErrors, logErrorDetails)
        // - displayErrorDetails: true = mostra detalhes do erro (ótimo para desenvolvimento)
        // - logErrors: true = registra erros no log do servidor
        // - logErrorDetails: true = registra detalhes completos no log
        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);

        // =============================================================
        // MANIPULADOR PERSONALIZADO DE ERROS
        // =============================================================
        // Define um manipulador de erro personalizado
        // Todas as exceções não capturadas passam por aqui
        $errorMiddleware->setDefaultErrorHandler(
            function (ServerRequestInterface $request, \Throwable $exception)  {
                // $request: a requisição que causou o erro
                // $exception: a exceção/erro que foi lançada
    
                // Cria uma resposta HTTP vazia usando a fábrica de respostas do Slim
                  $response = new \Slim\Psr7\Response();  
                  
                // Status HTTP padrão para erros internos do servidor (500 Internal Server Error)
                $status = 500;

                // =============================================================
                // CASO 1: ERRO ESPERADO/CONTROLADO (ErrorResponse)
                // =============================================================
                // Verifica se a exceção é do tipo ErrorResponse (erro esperado/controlado)
                // Exemplos: validação de dados, recurso não encontrado, permissão negada
                if ($exception instanceof ErrorResponse) {
                    // Formata resposta para erros esperados
                    $payload = [
                        'success' => false,                           // Indica falha na operação
                        'message' => $exception->getMessage(),        // Mensagem amigável para o usuário
                        'error' => $exception->getError() ?? (object) [], // Detalhes adicionais do erro
                    ];
                    $status = $exception->getHttpCode();              // Status HTTP específico (ex: 404, 400, 403)
                }
                // =============================================================
                // CASO 2: ERRO INESPERADO (qualquer outra exceção)
                // =============================================================
                else {
                    // Formata resposta para erros inesperados (ex: erro de banco de dados, PHP error)
                    $payload = [
                        'success' => false,                           // Indica falha na operação
                        'message' => $exception->getMessage(),        // Mensagem de erro técnica
                        'error' => [
                            'code' => $exception->getCode(),          // Código do erro
                            'sack' => $exception->getTrace(),
                            'file' => $exception->getFile(),          // Arquivo onde ocorreu
                            'line' => $exception->getLine(),          // Linha do erro
                        ],
                    ];
                    // Nota: Em produção, evite mostrar detalhes técnicos como file/line
                }

                // =============================================================
                // FORMATAÇÃO DA RESPOSTA
                // =============================================================
                // Converte o array $payload para JSON e escreve no corpo da resposta
                $response->getBody()->write(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                // Retorna a resposta configurada com headers apropriados
                return $response
                    ->withHeader('Content-Type', 'application/json')  // Indica que resposta é JSON
                    ->withStatus($status);                             // Define o status HTTP
            }
        );
    }

    /**
     * Inicia o servidor para processar requisições HTTP
     * 
     * Este método é chamado no arquivo principal (index.php) para
     * colocar a aplicação em execução.
     * 
     * Quando executado, o Slim:
     * 1. Analisa a requisição HTTP recebida
     * 2. Compara com as rotas configuradas
     * 3. Executa os middlewares na ordem correta
     * 4. Dispara o controller/closure da rota encontrada
     * 5. Retorna a resposta ao cliente
     * 
     * @return void
     */
    public function run(): void
    {
        // Executa a aplicação Slim, que processa a requisição atual
        // e retorna a resposta apropriada baseada nas rotas configuradas
        $this->app->run();
    }
}