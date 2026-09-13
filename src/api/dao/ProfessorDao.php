<?php

namespace Api\DAO;

use Api\Models\Professor;
use Api\Database\MysqlDatabase;
use Exception;
use PDO;

/**
 * Classe responsável pelo acesso aos dados da entidade Professor.
 *
 * Camadas:
 * Controller -> Service -> DAO -> Banco de Dados
 *
 * Objetivo:
 * Centralizar todas as operações SQL relacionadas à tabela Professor.
 */
class ProfessorDAO
{
    /**
     * Instância de conexão com banco de dados.
     *
     * @var MysqlDatabase
     */
    private MysqlDatabase $database;

    /**
     * Recebe a conexão via injeção de dependência.
     *
     * @param MysqlDatabase $databaseInstance
     */
    public function __construct(MysqlDatabase $databaseInstance)
    {
        $this->database = $databaseInstance;

        error_log("⬆️ ProfessorDAO::__construct()");
    }

     /**
     * Verifica as credenciais de um funcionário.
     *
     * @param Professor $Professor
     * @return Professor|null
     */
    public function verificarLogin(Professor $Professor): ?Professor
    {
        error_log("🟢 ProfessorDAO::verificarLogin()");

        $sql = "SELECT *
        FROM professor
        WHERE prof_nome = :prof_nome
        LIMIT 1";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':prof_nome' => $Professor->getprof_nome()
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        /**
         * Nome não encontrado.
         */
        if (!$row) {
            return null;
        }

        /**
         * Senha inválida.
         */
        if ((int) $Professor->getprof_id() !== (int) $row['prof_id']) {
            return null;
        }

        $ProfessorAutenticado = new Professor();

        $ProfessorAutenticado->setprof_id((int) $row['prof_id']);

        $ProfessorAutenticado->setprof_nome($row['prof_nome']);

        return $ProfessorAutenticado;
    }

    /**
     * Insere um novo Professor no banco.
     *
     * @param Professor $objProfessor
     * @return Professor gerado
     * @throws Exception
     */
    public function create(Professor $objProfessor): Professor
    {
        error_log("🟢 ProfessorDAO::create()");

        /**
         * SQL de inserção.
         */
        $sql = "
            INSERT INTO Professor (prof_nome)
            ValuES (:prof_nome)
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':prof_nome' => $objProfessor->getprof_nome()
        ];

        /**
         * Prepara e executa.
         */
        $stmt = $this->database->getConnection()->prepare($sql);

        if (!$stmt->execute($parametros)) {
            throw new Exception("Erro ao cadastrar Professor.");
        }

        /**
         * Retorna ID criado.
         */
        $novoID = (int) $this->database->getConnection()->lastInsertId();
        $objProfessor->setprof_id($novoID);
        return $objProfessor;
    }

    /**
     * Remove um Professor pelo ID.
     *
     * @param Professor $objProfessorModel
     * @return bool
     */
    public function delete(Professor $objProfessorModel): bool
    {
        error_log("🟢 ProfessorDAO::delete()");

        /**
         * SQL de exclusão.
         */
        $sql = "
            DELETE FROM Professor
            WHERE prof_id = :prof_id
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':prof_id' => $objProfessorModel->getprof_id()
        ];

        /**
         * Executa exclusão.
         */
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($parametros);

        /**
         * True se removeu registro.
         */
        return $stmt->rowCount() > 0;
    }

    /**
     * Atualiza um Professor existente.
     *
     * @param Professor $objProfessorModel
     * @return bool
     */
    public function update(Professor $objProfessorModel): bool
    {
        error_log("🟢 ProfessorDAO::update()");

        /**
         * SQL de atualização.
         */
        $sql = "
            UPDATE Professor
            SET prof_nome = :prof_nome
            WHERE prof_id = :prof_id
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':prof_nome' => $objProfessorModel->getprof_nome(),
            ':prof_id' => $objProfessorModel->getprof_id()
        ];

        /**
         * Executa atualização.
         */
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($parametros);

        /**
         * True se alterou registro.
         */
        return $stmt->rowCount() > 0;
    }

    /**
     * Retorna todos os Professorescadastrados.
     *
     * @return array
     */
    public function findAll(): array
    {
        error_log("🟢 ProfessorDAO::findAll()");

        /**
         * Consulta todos os registros.
         */
        $sql = "SELECT * FROM Professor";

        /**
         * Executa consulta.
         */
        $stmt = $this->database->getConnection()->query($sql);

        /**
         * Matriz de arrays.
         */
        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        /**
         * Lista final de objetos Professor.
         */
        $Professores = [];

        /**
         * Converte cada linha em objeto Professor.
         */
        foreach ($matrizArrays as $linhaMatriz) {
            $Professor = new Professor();

            $Professor->setprof_id((int) $linhaMatriz['prof_id']);
            $Professor->setprof_nome($linhaMatriz['prof_nome']);

            $Professores[] = $Professor;
        }

        /**
         * Retorna lista pronta.
         */
        return $Professores;
    }

    /**
     * Retorna total de Professorescadastrados.
     *
     * @return int
     */
    public function count(): int
    {
        error_log("🟢 ProfessorDAO::count()");

        /**
         * SQL de contagem.
         */
        $sql = "SELECT COUNT(*) AS qtd FROM Professor";

        /**
         * Executa consulta.
         */
        $stmt = $this->database->getConnection()->query($sql);

        /**
         * Resultado único.
         */
        $linhaMatriz = $stmt->fetch(\PDO::FETCH_ASSOC);

        /**
         * Retorna total.
         */
        return (int) $linhaMatriz['qtd'];
    }

    /**
     * Busca Professor pelo ID.
     *
     * @param int $prof_id
     * @return Professor|null
     */
    public function findById(int $prof_id): ?Professor
    {
        error_log("🟢 ProfessorDAO::findById()");

        /**
         * Busca reutilizando método genérico.
         */
        $resultado = $this->findByField('prof_id', $prof_id);

        /**
         * Se encontrou registro.
         */
        if (!empty($resultado)) {
            return $resultado[0];
        }

        /**
         * Não encontrado.
         */
        return null;
    }

    /**
     * Busca por campo específico.
     *
     * @param string $field
     * @param mixed $value
     * @return array
     * @throws Exception
     */
    public function findByField(string $field, $value): array
    {
        error_log("🟢 ProfessorDAO::findByField()");

        /**
         * Campos permitidos.
         */
        $camposPermitidos = [
            'prof_id',
            'prof_nome'
        ];

        /**
         * Valida campo informado.
         */
        if (!in_array($field, $camposPermitidos)) {
            throw new Exception("Campo inválido.");
        }

        /**
         * SQL dinâmica segura.
         */
        $sql = "SELECT * FROM Professor WHERE $field = :value";

        /**
         * Prepara consulta.
         */
        $stmt = $this->database->getConnection()->prepare($sql);

        /**
         * Executa busca.
         */
        $stmt->execute([
            ':value' => $value
        ]);

        /**
         * Matriz retornada.
         */
        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        /**
         * Lista final de objetos Professor.
         */
        $Professores= [];

        /**
         * Converte linhas em objetos.
         */
        foreach ($matrizArrays as $linhaMatriz) {
            $Professor = new Professor();

            $Professor->setprof_id((int) $linhaMatriz['prof_id']);
            $Professor->setprof_nome($linhaMatriz['prof_nome']);

            $Professores[] = $Professor;
        }

        /**
         * Retorna lista.
         */
        return $Professores;
    }
}