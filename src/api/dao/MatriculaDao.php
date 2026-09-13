<?php

namespace Api\DAO;

// Importações das classes necessárias
use Api\Models\Matricula;               // Model/Entidade Matricula
use Api\Models\Turma;     
use Api\Models\Aluno;  
use Api\Models\Disciplina;                // Model/Entidade Turma (para relacionamento)
use Api\Models\Professor;
use Api\Database\MysqlDatabase;           // Classe de conexão com o banco MySQL
use Exception;                            // Exceção genérica do PHP
use PDO;                                  // Classe nativa do PHP para acesso a banco

/**
 * Classe MatriculaDAO (Data Access Object)
 * 
 * Responsável por realizar operações no banco de dados relacionadas à entidade Matricula.
 * Implementa o padrão DAO, separando a lógica de acesso a dados da lógica de negócio.
 * 
 * ARQUITETURA EM CAMADAS:
 * [Controller] → [Service] → [DAO] → [Banco de Dados]
 *                           ↑
 *                      (Esta classe)
 * 
 * COMPLEXIDADES DESTE DAO:
 * - Relacionamento com a tabela Turma (JOIN)
 * - Hash de senha (segurança)
 * - Autenticação (login)
 * - Tratamento condicional no update (com/sem senha)
 */
class MatriculaDAO
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
        error_log("⬆️  MatriculaDAO::__construct()");

        $this->database = $databaseInstance;
    }

    /**
     * Cria um novo funcionário no banco de dados
     * 
     * Endpoint: POST /api/v1/Matriculas (chamado pelo Service)
     * 
     * DIFERENÇAS PARA O TurmaDAO::create:
     * - Mais campos (nome, email, senha, valeTransporte, Turma_id)
     * - Hash da senha com bcrypt (segurança)
     * - Uso de placeholders "?" (posicional) em vez de named placeholders
     * 
     * SEGURANÇA:
     * - password_hash() gera hash seguro da senha
     * - cost=12 define o custo computacional (quanto maior, mais seguro)
     * 
     * @param Matricula $Matricula Objeto Matricula com todos os dados
     * @return int ID do funcionário criado
     * @throws Exception Se a inserção falhar
     */
    public function create(Matricula $Matricula): int
    {
        error_log("🟢 MatriculaDAO::create()");



        // =============================================================
        // SQL com placeholders posicionais (?)
        // =============================================================
        $sql = "
            INSERT INTO matricula 
            (tur_id, alu_id) 
            VALUES (?, ?)
        ";

        // Array de parâmetros na mesma ordem dos placeholders
        $params = [
            $Matricula->getTurma()->gettur_id(),
            $Matricula->getAluno()->getalu_id(),
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
     * Endpoint: DELETE /api/v1/Matriculas/{matr_id}
     * 
     * @param Matricula $Matricula Objeto Matricula com ID definido
     * @return bool True se excluiu, False se não encontrou
     */
    public function delete(Matricula $Matricula): bool
    {
        error_log("🟢 MatriculaDAO::delete()");

        $sql = "DELETE FROM matricula WHERE matr_id = ?";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Matricula->getmatr_id()]);

        // rowCount() indica quantas linhas foram afetadas
        return $stmt->rowCount() > 0;
    }

    /**
     * Atualiza os dados de um funcionário
     * 
     * Endpoint: PUT /api/v1/Matriculas/{matr_id}
     * 
     * COMPLEXIDADE:
     * - Se a senha for fornecida (não vazia), atualiza também a senha
     * - Se a senha não for fornecida, mantém a senha atual
     * 
     * Isso permite que o frontend não precise enviar a senha
     * em toda atualização (apenas quando quer mudar)
     *
     * @param Matricula $Matricula Objeto Matricula com dados atualizados
     * @return bool True se atualizou, False se não encontrou
     */
    public function update(Matricula $Matricula): bool
    {
        error_log("🟢 MatriculaDAO::update()");

        $pdo = $this->database->getConnection();

            $sql = "
                UPDATE matricula 
                SET tur_id=?, alu_id=? 
                WHERE matr_id=?
            ";
            $params = [
                $Matricula->getTurma()->gettur_id(),
                $Matricula->getAluno()->getalu_id(),
                $Matricula->getmatr_id(),
            ];
        // Executa a query apropriada
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * Retorna todos os Matriculas com seus respectivos Turmas
     * 
     * Endpoint: GET /api/v1/Matriculas
     * 
     * DIFERENÇA PARA O TurmaDAO::findAll:
     * - Faz JOIN com a tabela Turma para trazer o nome do Turma
     * - Converte os resultados em objetos (não arrays)
     * 
     * @return array Array de objetos Matricula (cada um com seu Turma)
     */
    public function findAll(): array
    {
        error_log("🟢 MatriculaDAO::findAll()");

        // =============================================================
        // JOIN para trazer dados relacionados
        // =============================================================
       $sql = "
            SELECT 
                matricula.matr_id, 
                matricula.tur_id, 
                turma.dis_id,
                disciplina.dis_nome, 
                matricula.alu_id, 
                aluno.alu_nome,
                professor.prof_id,
                professor.prof_nome   
            FROM matricula
            JOIN turma ON matricula.tur_id = turma.tur_id
            JOIN aluno ON matricula.alu_id = aluno.alu_id
            JOIN disciplina ON turma.dis_id = disciplina.dis_id
            JOIN professor ON turma.prof_id = professor.prof_id
            ORDER BY matricula.matr_id ASC
        ";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =============================================================
        // Converte cada linha em objetos Turma e Matricula
        // =============================================================
        $result = [];
        foreach ($rows as $row) {
            
            $Disciplina = new Disciplina();
            if (isset($row['dis_id'])) {
            $Disciplina->setdis_id((int) $row['dis_id']);
            }
            $Disciplina->setdis_nome($row['dis_nome']);

            $Professor = new Professor();
            if (isset($row['prof_id'])) {
            $Professor->setprof_id((int) $row['prof_id']);
            }
            $Professor->setprof_nome($row['prof_nome']);

            $Turma = new Turma();
            $Turma->settur_id((int) $row['tur_id']);
            $Turma->setDisciplina($Disciplina);
            $Turma->setProfessor($Professor);

            $Aluno = new Aluno();
            $Aluno->setalu_id((int) $row['alu_id']);
            $Aluno->setalu_nome($row['alu_nome']);

            // Cria objeto Matricula com os dados da tabela
            $Matricula = new Matricula();
            $Matricula->setmatr_id((int) $row['matr_id']);

            // Associa o Turma ao funcionário
            $Matricula->setTurma($Turma);
            $Matricula->setAluno($Aluno);


            $result[] = $Matricula;
        }

        return $result;
    }

    /**
     * Retorna a quantidade total de funcionários
     * 
     * Endpoint: GET /api/v1/Matriculas/count
     * 
     * @return int Número total de funcionários
     */
    public function count(): int
    {
        error_log("🟢 MatriculaDAO::count()");

        $sql = "SELECT COUNT(*) AS qtd FROM matricula";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $row['qtd'];
    }

    /**
     * Busca um funcionário específico pelo ID
     * 
     * Endpoint: GET /api/v1/Matriculas/{matr_id}
     * 
     * REUTILIZAÇÃO:
     * - Aproveita o método genérico findByField
     * - Retorna o primeiro resultado ou null
     *
     * @param int $matr_id ID do funcionário
     * @return Matricula|null Objeto Matricula ou null se não encontrado
     */
    public function findById(int $matr_id): ?Matricula
    {
        $result = $this->findByField('matr_id', $matr_id);
        return $result[0] ?? null; // Retorna primeiro ou null
    }

    /**
     * Busca funcionários por um campo específico (método genérico)
     * 
     * @param string $field Nome do campo (deve estar em $allowedFields)
     * @param mixed $value Valor a ser buscado
     * @return array Array de objetos Matricula
     * @throws Exception Se o campo não for permitido
     */
    public function findByField(string $field, $value): array
    {
        error_log("🟢 MatriculaDAO::findByField() - Campo: $field, Valor: $value");

        // =============================================================
        // SEGURANÇA: Whitelist de campos permitidos
        // =============================================================
        $allowedFields = ['matr_id', 'tur_id', 'alu_id'];
        if (!in_array($field, $allowedFields)) {
            throw new Exception("Campo inválido para busca");
        }

        // =============================================================
        // Busca apenas na tabela Matricula (sem JOIN)
        // =============================================================
        $sql = "SELECT 
                    matricula.matr_id, 
                    matricula.tur_id,  
                    turma.dis_id,
                    disciplina.dis_nome,
                    matricula.alu_id, 
                    aluno.alu_nome,
                    professor.prof_id,
                    professor.prof_nome
                FROM matricula
                JOIN turma ON matricula.tur_id = turma.tur_id
                JOIN disciplina ON turma.dis_id = disciplina.dis_id
                JOIN professor ON turma.prof_id = professor.prof_id
                JOIN aluno ON matricula.alu_id = aluno.alu_id WHERE $field = ?
                ORDER BY matricula.matr_id ASC";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$value]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =============================================================
        // Converte cada linha em objeto Matricula
        // =============================================================
        $result = [];
        foreach ($rows as $row) {
            // Cria objeto Turma apenas com o ID (sem nome)
            $Disciplina = new Disciplina();
            if (isset($row['dis_id'])) {
            $Disciplina->setdis_id((int) $row['dis_id']);
            }
            $Disciplina->setdis_nome($row['dis_nome']);

            $Professor = new Professor();
            if (isset($row['prof_id'])) {
                $Professor->setprof_id((int) $row['prof_id']);
            }
            $Professor->setprof_nome($row['prof_nome']);

            $Turma = new Turma();
            $Turma->settur_id((int) $row['tur_id']);
            $Turma->setDisciplina($Disciplina);
            $Turma->setProfessor($Professor);

            // NOTA: O nome do Turma não é carregado aqui (falta JOIN)
            $Aluno = new Aluno();
            $Aluno->setalu_id((int) $row['alu_id']);
            $Aluno->setalu_nome($row['alu_nome']);

            // Cria objeto Matricula
            $Matricula = new Matricula();
            $Matricula->setmatr_id((int) $row['matr_id']);
           
            $Matricula->setTurma($Turma);
            $Matricula->setAluno($Aluno);
            //$Matricula->setDisciplina($Disciplina);
            $result[] = $Matricula;
        }

        return $result;
    }

    
}