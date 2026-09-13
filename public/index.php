<?php

// Carrega o autoloader do Composer, que é responsável por incluir automaticamente
// todas as classes necessárias baseado nas dependências do projeto
require __DIR__ . '/../vendor/autoload.php';

// Importa as classes necessárias usando 'use' statements para facilitar a referência
use DI\ContainerBuilder;           // Construtor do container de injeção de dependência
use Slim\Factory\AppFactory;       // Fábrica para criar a aplicação Slim
use Api\Database\MysqlDatabase;    // Classe personalizada para conexão com MySQL
use Api\Server\Server;             // Classe responsável por iniciar o servidor

// =========================
// 1. CONFIGURAÇÃO DO CONTAINER DE INJEÇÃO DE DEPENDÊNCIA
// =========================
// O Container é como um "cérebro" que gerencia todas as classes do sistema.
// Ele sabe como criar e fornecer instâncias das classes quando necessário.

$builder = new ContainerBuilder();

// 🔥 Habilita o Autowiring (injeção automática de dependências)
// Isso significa que o container vai tentar resolver automaticamente as dependências
// das classes sem precisarmos configurar manualmente
// Por padrão, o Autowiring cria uma NOVA instância cada vez que a classe é solicitada
$builder->useAutowiring(true);

// 👉 Configuração MANUAL de dependências específicas
// Aqui definimos manualmente como criar o MysqlDatabase porque ele precisa
// de parâmetros de configuração (host, user, etc.) que não podem ser resolvidos
// automaticamente pelo Autowiring
// utilizamos quando queremos que uma instância seja utilizada sempre que necessário e não varias instâncias.

$mysqlDatabase = new MysqlDatabase([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'universidadeWeb',
]);

// Constrói o container com todas as configurações definidas
$container = $builder->build();

$container->set(MysqlDatabase::class, $mysqlDatabase);

// =========================
// 2. CONFIGURAÇÃO DA APLICAÇÃO SLIM
// =========================
// Slim é um micro-framework para APIs e aplicações web

// Associa o container que criamos à fábrica do Slim
// Isso permite que o Slim use nosso container para resolver dependências
AppFactory::setContainer($container);

// Cria uma nova instância da aplicação Slim
// A aplicação é responsável por rotear requisições HTTP para os controllers adequados
$app = AppFactory::create();

// 🔥 Registra a aplicação no container (PASSO ESSENCIAL)
// Isso torna a instância do Slim disponível para injeção em outras classes.
// Quando uma classe precisar do Slim\App, o container fornecerá esta instância
//a instancia de App é utilizada nos roteadores, e deve ser a mesma instância
$container->set(\Slim\App::class, $app);

// =========================
// 3. INICIALIZAÇÃO DO SERVIDOR
// =========================
// Finalmente, iniciamos o servidor para começar a receber requisições

// Pede ao container para fornecer uma instância da classe Server
// O container automaticamente injetará todas as dependências que o Server precisar
$server = $container->get(Server::class);

// Executa o servidor, que começará a escutar requisições HTTP
// e as encaminhará para os controllers apropriados
$server->run();




/*


Explicação do Fluxo Completo:
Autoload → Carrega automaticamente todas as classes necessárias

Container DI → Configura o "cérebro" que gerencia as dependências

Slim App → Cria a aplicação que vai rotear as requisições

Server → Inicia o servidor para receber requisições HTTP

Por que o Autowiring é útil?
Evita ter que configurar manualmente cada classe

Se uma classe UsuarioController precisa de UsuarioService, o container automaticamente cria o UsuarioService e injeta no controller

Reduz drasticamente o código de configuração

Por que configurar o MysqlDatabase manualmente?
Classes que precisam de valores específicos (como senha de banco) não podem ser resolvidas automaticamente - você precisa dizer explicitamente quais valores usar.

*/