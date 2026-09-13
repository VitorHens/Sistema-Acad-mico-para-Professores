<?php

namespace Api\DAO;

// Importações das classes necessárias
use Api\Models\Nota;               // Model/Entidade Nota
use Api\Models\Matricula;               // Model/Entidade Matricula
use Api\Models\Turma;     
use Api\Models\Aluno;  
use Api\Models\Disciplina;    
use Api\Models\Professor;             // Model/Entidade Turma (para relacionamento)
use Api\Database\MysqlDatabase;           // Classe de conexão com o banco MySQL
use Exception;                            // Exceção genérica do PHP
use PDO;                                  // Classe nativa do PHP para acesso a banco

/**
 * Classe NotaDAO (Data Access Object)
 * 
 * Responsável por realizar operações no banco de dados relacionadas à entidade Nota.
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
class NotaDAO
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
        error_log("⬆️  NotaDAO::__construct()");

        $this->database = $databaseInstance;
    }

    /**
     * Cria um novo funcionário no banco de dados
     * 
     * Endpoint: POST /api/v1/Notas (chamado pelo Service)
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
     * @param Nota $Nota Objeto Nota com todos os dados
     * @return int ID do funcionário criado
     * @throws Exception Se a inserção falhar
     */
    public function create(Nota $Nota): int
    {
        error_log("🟢 NotaDAO::create()");



        // =============================================================
        // SQL com placeholders posicionais (?)
        // =============================================================
        $sql = "
            INSERT INTO notas_frequencia 
            (fre , nota, matr_id) 
            VALUES (?, ?, ?)
        ";

        // Array de parâmetros na mesma ordem dos placeholders
        $params = [
            $Nota->getfre(),
            $Nota->getNota(),  
            $Nota->getmatr_id(),
            
        ];

        // Obtém conexão PDO e executa
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Obtém o ID gerado (auto_increment)
        $insertId = $pdo->lastInsertId();

        if (!$insertId) {
            throw new Exception("Falha ao inserir nota/frequencia");
        }

        return (int) $insertId;
    }

    /**
     * Remove um funcionário pelo ID
     * 
     * Endpoint: DELETE /api/v1/Notas/{matr_id}
     * 
     * @param Nota $Nota Objeto Nota com ID definido
     * @return bool True se excluiu, False se não encontrou
     */
    public function delete(Nota $Nota): bool
    {
        error_log("🟢 NotaDAO::delete()");

        $sql = "DELETE FROM notas_frequencia  WHERE fre_id = ?";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Nota->getfre_id()]);

        return $stmt->rowCount() > 0;

    }
        // rowCount() indica quantas linhas foram afetadas
        /**
        * Deleta todas as notas/frequências de uma matrícula
        * 
        * @param int $matr_id ID da matrícula
        * @return bool True se deletou pelo menos uma, False se não encontrou
        */
    public function deleteByMatriculaId(int $matr_id): bool
    {
        error_log("🟢 NotaDAO::deleteByMatriculaId() - matr_id: $matr_id");
    
        $sql = "DELETE FROM notas_frequencia WHERE matr_id = ?";
    
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$matr_id]);
    
        // Retorna true se deletou pelo menos um registro
        return $stmt->rowCount() > 0;
    }
    
    
    /**
     * Atualiza os dados de um funcionário
     * 
     * Endpoint: PUT /api/v1/Notas/{matr_id}
     * 
     * COMPLEXIDADE:
     * - Se a senha for fornecida (não vazia), atualiza também a senha
     * - Se a senha não for fornecida, mantém a senha atual
     * 
     * Isso permite que o frontend não precise enviar a senha
     * em toda atualização (apenas quando quer mudar)
     *
     * @param Nota $Nota Objeto Nota com dados atualizados
     * @return bool True se atualizou, False se não encontrou
     */
    public function update(Nota $Nota): bool
    {
        
        error_log("🟢 NotaDAO::update()");

        $pdo = $this->database->getConnection();

            $sql = "
                UPDATE notas_frequencia 
                SET fre =?, nota =?, matr_id = ? 
                WHERE fre_id=?
            ";
            $params = [
                $Nota->getfre(),
                $Nota->getNota(),  
                $Nota->getMatricula()->getmatr_id(),
                $Nota->getfre_id(),
            ];

        // Executa a query apropriada
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * Retorna todos os Notas com seus respectivos Turmas
     * 
     * Endpoint: GET /api/v1/Notas
     * 
     * DIFERENÇA PARA O TurmaDAO::findAll:
     * - Faz JOIN com a tabela Turma para trazer o nome do Turma
     * - Converte os resultados em objetos (não arrays)
     * 
     * @return array Array de objetos Nota (cada um com seu Turma)
     */
    public function findAll(): array
    {
        error_log("🟢 NotaDAO::findAll()");

        // =============================================================
        // JOIN para trazer dados relacionados
        // =============================================================
       $sql = "
            SELECT 
                notas_frequencia.fre_id, 
                notas_frequencia.fre, 
                notas_frequencia.nota, 
                notas_frequencia.matr_id,
                matricula.tur_id,
                matricula.alu_id,
                turma.dis_id,
                turma.prof_id,
                disciplina.dis_nome,  
                aluno.alu_nome,
                professor.prof_id,
                professor.prof_nome
            FROM notas_frequencia 
            JOIN matricula ON notas_frequencia.matr_id = matricula.matr_id
            JOIN turma ON matricula.tur_id = turma.tur_id
            JOIN aluno ON matricula.alu_id = aluno.alu_id
            JOIN disciplina ON turma.dis_id = disciplina.dis_id
            JOIN professor ON turma.prof_id = professor.prof_id
            ORDER BY notas_frequencia.fre_id ASC
        ";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =============================================================
        // Converte cada linha em objetos Turma e Nota
        // =============================================================
        $result = [];
        foreach ($rows as $row) {
            // Cria objeto Turma com os dados do JOIN
             $Disciplina = new Disciplina();
            if (isset($row['dis_id'])) {
            $Disciplina->setdis_id((int) $row['dis_id']);
            }
            $Disciplina->setdis_nome($row['dis_nome']);

            $Professor = new Professor();
            $Professor->setprof_id((int) $row['prof_id']);
            $Professor->setprof_nome($row['prof_nome']);


            $Turma = new Turma();
            $Turma->settur_id((int) $row['tur_id']);
            $Turma->setDisciplina($Disciplina);
            $Turma->setProfessor($Professor);

            $Aluno = new Aluno();
            $Aluno->setalu_id((int) $row['alu_id']);
            $Aluno->setalu_nome($row['alu_nome']);

            $Matricula = new Matricula();
            $Matricula->setmatr_id((int) $row['matr_id']);
            $Matricula->setTurma($Turma);
            $Matricula->setAluno($Aluno);

            // Cria objeto Nota com os dados da tabela
            $Nota = new Nota();
            $Nota->setfre_id((int) $row['fre_id']);
            $Nota->setfre((float) $row['fre']);
            $Nota->setNota((int) $row['nota']);
            $Nota->setmatr_id((int) $row['matr_id']);
            $Nota->setMatricula($Matricula);

            $result[] = $Nota;
        }

        return $result;
    }

    /**
     * Retorna a quantidade total de funcionários
     * 
     * Endpoint: GET /api/v1/Notas/count
     * 
     * @return int Número total de funcionários
     */
    public function count(): int
    {
        error_log("🟢 NotaDAO::count()");

        $sql = "SELECT COUNT(*) AS qtd FROM notas_frequencia ";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $row['qtd'];
    }

    /**
     * Busca um funcionário específico pelo ID
     * 
     * Endpoint: GET /api/v1/Notas/{matr_id}
     * 
     * REUTILIZAÇÃO:
     * - Aproveita o método genérico findByField
     * - Retorna o primeiro resultado ou null
     *
     * @param int $fre_id ID do funcionário
     * @return Nota|null Objeto Nota ou null se não encontrado
     */
    public function findById(int $fre_id): ?Nota
    {
        $result = $this->findByField('fre_id', $fre_id);
        return $result[0] ?? null; // Retorna primeiro ou null
    }
    /** 
    * Busca notas/frequências por matrícula
    *   
    * @param int $matr_id ID da matrícula
    * @return array Array de objetos Nota
    */

    public function findByMatriculaId(int $matr_id): array
    {
    error_log("🟢 NotaDAO::findByMatriculaId() - matr_id: $matr_id");
    
    return $this->findByField('matr_id', $matr_id);
    }
    /**
     * Busca nota/frequencia por um campo específico (método genérico)
     * 
     * @param string $field Nome do campo (deve estar em $allowedFields)
     * @param mixed $value Valor a ser buscado
     * @return array Array de objetos Nota
     * @throws Exception Se o campo não for permitido
     */
    public function findByField(string $field, $value): array
    {
        error_log("🟢 NotaDAO::findByField() - Campo: $field, Valor: $value");

        // =============================================================
        // SEGURANÇA: Whitelist de campos permitidos
        // =============================================================
        $allowedFields = ['fre_id', 'matr_id', 'nota'];
        if (!in_array($field, $allowedFields)) {
            throw new Exception("Campo inválido para busca");
        }

        $whereField = ($field === 'matr_id') ? 'notas_frequencia.matr_id' : $field;
        // =============================================================
        // Busca apenas na tabela notas_frequencia  (sem JOIN)
        // =============================================================
        $sql = "SELECT 
                    notas_frequencia.fre_id, 
                    notas_frequencia.fre, 
                    notas_frequencia.nota, 
                    notas_frequencia.matr_id,
                    matricula.tur_id,
                    matricula.alu_id,
                    turma.dis_id,
                    turma.prof_id,
                    disciplina.dis_nome,  
                    professor.prof_id,
                    professor.prof_nome,
                    aluno.alu_nome
                FROM notas_frequencia 
                JOIN matricula ON notas_frequencia.matr_id = matricula.matr_id
                JOIN turma ON matricula.tur_id = turma.tur_id
                JOIN aluno ON matricula.alu_id = aluno.alu_id
                JOIN professor ON turma.prof_id = professor.prof_id
                JOIN disciplina ON turma.dis_id = disciplina.dis_id WHERE $whereField = ?
                ORDER BY notas_frequencia.fre_id ASC";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$value]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =============================================================
        // Converte cada linha em objeto Nota
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
            $Professor->setprof_id((int) $row['prof_id']);
            $Professor->setprof_nome($row['prof_nome']);

            $Turma = new Turma();
            $Turma->settur_id((int) $row['tur_id']);
            $Turma->setDisciplina($Disciplina);
            $Turma->setProfessor($Professor);

            $Aluno = new Aluno();
            $Aluno->setalu_id((int) $row['alu_id']);
            $Aluno->setalu_nome($row['alu_nome']);

            $Matricula = new Matricula();
            $Matricula->setmatr_id((int) $row['matr_id']);
            $Matricula->setTurma($Turma);
            $Matricula->setAluno($Aluno);

            // Cria objeto Nota com os dados da tabela
            $Nota = new Nota();
            $Nota->setfre_id((int) $row['fre_id']);
            $Nota->setfre((float) $row['fre']);
            $Nota->setNota((int) $row['nota']);
            $Nota->setmatr_id((int) $row['matr_id']);
            $Nota->setMatricula($Matricula);
            
            $result[] = $Nota;
        }

        return $result;
    }

    
}