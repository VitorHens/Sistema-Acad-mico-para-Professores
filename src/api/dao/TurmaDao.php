<?php

namespace Api\DAO;

// Importações das classes necessárias
use Api\Models\Turma;               // Model/Entidade Turma
use Api\Models\Disciplina;     
use Api\Models\Professor;                  // Model/Entidade Disciplina (para relacionamento)
use Api\Database\MysqlDatabase;           // Classe de conexão com o banco MySQL
use Exception;                            // Exceção genérica do PHP
use PDO;                                  // Classe nativa do PHP para acesso a banco

/**
 * Classe TurmaDAO (Data Access Object)
 * 
 * Responsável por realizar operações no banco de dados relacionadas à entidade Turma.
 * Implementa o padrão DAO, separando a lógica de acesso a dados da lógica de negócio.
 * 
 * ARQUITETURA EM CAMADAS:
 * [Controller] → [Service] → [DAO] → [Banco de Dados]
 *                           ↑
 *                      (Esta classe)
 * 
 * COMPLEXIDADES DESTE DAO:
 * - Relacionamento com a tabela Disciplina (JOIN)
 * - Hash de senha (segurança)
 * - Autenticação (login)
 * - Tratamento condicional no update (com/sem senha)
 */
class TurmaDAO
{
    /** 
     * Instância da conexão com o banco de dados
     * 
     * @var MysqlDatabase 
     */
    private MysqlDatabase $database;

    /**
     * Construtor do DAO, recebe a instância de MysqlDatabase via injeção de dependência
     *
     * @param MysqlDatabase $databaseInstance Instância da conexão com o banco
     */
    public function __construct(MysqlDatabase $databaseInstance)
    {
        // Log para debug - ⬆️ indica construção/instanciação
        error_log("⬆️  TurmaDAO::__construct()");

        $this->database = $databaseInstance;
    }

    /**
     * Cria um novo funcionário no banco de dados
     * 
     * Endpoint: POST /api/v1/Turmas (chamado pelo Service)
     * 
     * DIFERENÇAS PARA O DisciplinaDAO::create:
     * - Mais campos (nome, email, senha, valeTransporte, Disciplina_id)
     * - Hash da senha com bcrypt (segurança)
     * - Uso de placeholders "?" (posicional) em vez de named placeholders
     * 
     * SEGURANÇA:
     * - password_hash() gera hash seguro da senha
     * - cost=12 define o custo computacional (quanto maior, mais seguro)
     * 
     * @param Turma $Turma Objeto Turma com todos os dados
     * @return int ID do funcionário criado
     * @throws Exception Se a inserção falhar
     */
    public function create(Turma $Turma): int
    {
        error_log("🟢 TurmaDAO::create()");



        // =============================================================
        // SQL com placeholders posicionais (?)
        // =============================================================
        $sql = "
            INSERT INTO turma 
            (dis_id, prof_id) 
            VALUES (?, ?)
        ";

        // Array de parâmetros na mesma ordem dos placeholders
        $params = [
            $Turma->getDisciplina()->getdis_id(),
            $Turma->getProfessor()->getprof_id(),
        ];

        // Obtém conexão PDO e executa
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Obtém o ID gerado (auto_increment)
        $insertId = $pdo->lastInsertId();

        if (!$insertId) {
            throw new Exception("Falha ao inserir funcionário");
        }

        return (int) $insertId;
    }

    /**
     * Remove um funcionário pelo ID
     * 
     * Endpoint: DELETE /api/v1/Turmas/{tur_id}
     * 
     * @param Turma $Turma Objeto Turma com ID definido
     * @return bool True se excluiu, False se não encontrou
     */
    public function delete(Turma $Turma): bool
    {
        error_log("🟢 TurmaDAO::delete()");

        $sql = "DELETE FROM turma WHERE tur_id = ?";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Turma->gettur_id()]);

        // rowCount() indica quantas linhas foram afetadas
        return $stmt->rowCount() > 0;
    }

    /**
     * Atualiza os dados de um funcionário
     * 
     * Endpoint: PUT /api/v1/Turmas/{tur_id}
     * 
     * COMPLEXIDADE:
     * - Se a senha for fornecida (não vazia), atualiza também a senha
     * - Se a senha não for fornecida, mantém a senha atual
     * 
     * Isso permite que o frontend não precise enviar a senha
     * em toda atualização (apenas quando quer mudar)
     *
     * @param Turma $Turma Objeto Turma com dados atualizados
     * @return bool True se atualizou, False se não encontrou
     */
    public function update(Turma $Turma): bool
    {
        error_log("🟢 TurmaDAO::update()");

        $pdo = $this->database->getConnection();

            $sql = "
                UPDATE turma 
                SET dis_id=?, prof_id=? 
                WHERE tur_id=?
            ";
            $params = [
                $Turma->getDisciplina()->getdis_id(),
                $Turma->getProfessor()->getprof_id(),
                $Turma->gettur_id(),
            ];
        // Executa a query apropriada
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * Retorna todos os turmas com seus respectivos Disciplinas
     * 
     * Endpoint: GET /api/v1/Turmas
     * 
     * DIFERENÇA PARA O DisciplinaDAO::findAll:
     * - Faz JOIN com a tabela Disciplina para trazer o nome do Disciplina
     * - Converte os resultados em objetos (não arrays)
     * 
     * @return array Array de objetos Turma (cada um com seu Disciplina)
     */
    public function findAll(): array
    {
        error_log("🟢 TurmaDAO::findAll()");

        // =============================================================
        // JOIN para trazer dados relacionados
        // =============================================================
       $sql = "
            SELECT 
                turma.tur_id, 
                turma.dis_id, 
                disciplina.dis_nome,  
                turma.prof_id, 
                professor.prof_nome   
            FROM turma
            JOIN disciplina ON turma.dis_id = disciplina.dis_id
            JOIN professor ON turma.prof_id = professor.prof_id
            ORDER BY turma.tur_id ASC
        ";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =============================================================
        // Converte cada linha em objetos Disciplina e Turma
        // =============================================================
        $result = [];
        foreach ($rows as $row) {
            // Cria objeto Disciplina com os dados do JOIN
            $Disciplina = new Disciplina();
            $Disciplina->setdis_id((int) $row['dis_id']);
            $Disciplina->setdis_nome($row['dis_nome']);

            $Professor = new Professor();
            $Professor->setprof_id((int) $row['prof_id']);
            $Professor->setprof_nome($row['prof_nome']);

            // Cria objeto Turma com os dados da tabela
            $Turma = new Turma();
            $Turma->settur_id((int) $row['tur_id']);

            // Associa o Disciplina ao funcionário
            $Turma->setDisciplina($Disciplina);
            $Turma->setProfessor($Professor);

            $result[] = $Turma;
        }

        return $result;
    }

    /**
     * Retorna a quantidade total de funcionários
     * 
     * Endpoint: GET /api/v1/Turmas/count
     * 
     * @return int Número total de funcionários
     */
    public function count(): int
    {
        error_log("🟢 TurmaDAO::count()");

        $sql = "SELECT COUNT(*) AS qtd FROM turma";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $row['qtd'];
    }

    /**
     * Busca um funcionário específico pelo ID
     * 
     * Endpoint: GET /api/v1/Turmas/{tur_id}
     * 
     * REUTILIZAÇÃO:
     * - Aproveita o método genérico findByField
     * - Retorna o primeiro resultado ou null
     *
     * @param int $tur_id ID do funcionário
     * @return Turma|null Objeto Turma ou null se não encontrado
     */
    public function findById(int $tur_id): ?Turma
    {
        $result = $this->findByField('tur_id', $tur_id);
        return $result[0] ?? null; // Retorna primeiro ou null
    }

    /**
     * Busca funcionários por um campo específico (método genérico)
     * 
     * @param string $field Nome do campo (deve estar em $allowedFields)
     * @param mixed $value Valor a ser buscado
     * @return array Array de objetos Turma
     * @throws Exception Se o campo não for permitido
     */
    public function findByField(string $field, $value): array
    {
        error_log("🟢 TurmaDAO::findByField() - Campo: $field, Valor: $value");

        // =============================================================
        // SEGURANÇA: Whitelist de campos permitidos
        // =============================================================
        $allowedFields = ['tur_id', 'dis_id', 'prof_id'];
        if (!in_array($field, $allowedFields)) {
            throw new Exception("Campo inválido para busca");
        }

        // =============================================================
        // Busca apenas na tabela Turma (sem JOIN)
        // =============================================================
        $sql = "SELECT 
                    turma.tur_id, 
                    turma.dis_id, 
                    disciplina.dis_nome, 
                    turma.prof_id, 
                    professor.prof_nome
                FROM turma
                JOIN disciplina ON turma.dis_id = disciplina.dis_id
                JOIN professor ON turma.prof_id = professor.prof_id WHERE $field = ?
                ORDER BY turma.tur_id ASC";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$value]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =============================================================
        // Converte cada linha em objeto Turma
        // =============================================================
        $result = [];
        foreach ($rows as $row) {
            // Cria objeto Disciplina apenas com o ID (sem nome)
            $Disciplina = new Disciplina();
            $Disciplina->setdis_id((int) $row['dis_id']);
            $Disciplina->setdis_nome($row['dis_nome']);
            // NOTA: O nome do Disciplina não é carregado aqui (falta JOIN)
            $Professor = new Professor();
            $Professor->setprof_id((int) $row['prof_id']);
            $Professor->setprof_nome($row['prof_nome']);

            // Cria objeto Turma
            $Turma = new Turma();
            $Turma->settur_id((int) $row['tur_id']);
           
            $Turma->setDisciplina($Disciplina);
            $Turma->setProfessor($Professor);

            $result[] = $Turma;
        }

        return $result;
    }

    
}