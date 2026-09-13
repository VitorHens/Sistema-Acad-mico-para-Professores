<?php

namespace Api\DAO;
use Api\Models\Disciplina;
use Api\Database\MysqlDatabase;
use Exception;

/**
 * Classe responsável pelo acesso aos dados da entidade Disciplina.
 *
 * Camadas:
 * Controller -> Service -> DAO -> Banco de Dados
 *
 * Objetivo:
 * Centralizar todas as operações SQL relacionadas à tabela Disciplina.
 */
class DisciplinaDAO
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

        error_log("⬆️ DisciplinaDAO::__construct()");
    }

    /**
     * Insere um novo Disciplina no banco.
     *
     * @param Disciplina $objDisciplina
     * @return Disciplina gerado
     * @throws Exception
     */
    public function create(Disciplina $objDisciplina): Disciplina
    {
        error_log("🟢 DisciplinaDAO::create()");

        /**
         * SQL de inserção.
         */
        $sql = "
            INSERT INTO disciplina (dis_nome)
            VALUES (:dis_nome)
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':dis_nome' => $objDisciplina->getdis_nome()
        ];

        /**
         * Prepara e executa.
         */
        $stmt = $this->database->getConnection()->prepare($sql);

        if (!$stmt->execute($parametros)) {
            throw new Exception("Erro ao cadastrar Disciplina.");
        }

        /**
         * Retorna ID criado.
         */
        $novoID = (int) $this->database->getConnection()->lastInsertId();
        $objDisciplina->setdis_id($novoID);
        return $objDisciplina;
    }

    /**
     * Remove um Disciplina pelo ID.
     *
     * @param Disciplina $objDisciplinaModel
     * @return bool
     */
    public function delete(Disciplina $objDisciplinaModel): bool
    {
        error_log("🟢 DisciplinaDAO::delete()");

        /**
         * SQL de exclusão.
         */
        $sql = "
            DELETE FROM disciplina
            WHERE dis_id = :dis_id
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':dis_id' => $objDisciplinaModel->getdis_id()
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
     * Atualiza um Disciplina existente.
     *
     * @param Disciplina $objDisciplinaModel
     * @return bool
     */
    public function update(Disciplina $objDisciplinaModel): bool
    {
        error_log("🟢 DisciplinaDAO::update()");

        /**
         * SQL de atualização.
         */
        $sql = "
            UPDATE disciplina
            SET dis_nome = :dis_nome
            WHERE dis_id = :dis_id
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':dis_nome' => $objDisciplinaModel->getdis_nome(),
            ':dis_id' => $objDisciplinaModel->getdis_id()
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
     * Retorna todos os Disciplinas cadastrados.
     *
     * @return array
     */
    public function findAll(): array
    {
        error_log("🟢 DisciplinaDAO::findAll()");

        /**
         * Consulta todos os registros.
         */
        $sql = "SELECT * FROM disciplina";

        /**
         * Executa consulta.
         */
        $stmt = $this->database->getConnection()->query($sql);

        /**
         * Matriz de arrays.
         */
        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        /**
         * Lista final de objetos Disciplina.
         */
        $Disciplinas = [];

        /**
         * Converte cada linha em objeto Disciplina.
         */
        foreach ($matrizArrays as $linhaMatriz) {
            $Disciplina = new Disciplina();

            $Disciplina->setdis_id((int) $linhaMatriz['dis_id']);
            $Disciplina->setdis_nome($linhaMatriz['dis_nome']);

            $Disciplinas[] = $Disciplina;
        }

        /**
         * Retorna lista pronta.
         */
        return $Disciplinas;
    }

    /**
     * Retorna total de Disciplinas cadastrados.
     *
     * @return int
     */
    public function count(): int
    {
        error_log("🟢 DisciplinaDAO::count()");

        /**
         * SQL de contagem.
         */
        $sql = "SELECT COUNT(*) AS qtd FROM disciplina";

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
     * Busca Disciplina pelo ID.
     *
     * @param int $dis_id
     * @return Disciplina|null
     */
    public function findById(int $dis_id): ?Disciplina
    {
        error_log("🟢 DisciplinaDAO::findById()");

        /**
         * Busca reutilizando método genérico.
         */
        $resultado = $this->findByField('dis_id', $dis_id);

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
        error_log("🟢 DisciplinaDAO::findByField()");

        /**
         * Campos permitidos.
         */
        $camposPermitidos = [
            'dis_id',
            'dis_nome'
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
        $sql = "SELECT * FROM disciplina WHERE $field = :value";

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
         * Lista final de objetos Disciplina.
         */
        $Disciplinas = [];

        /**
         * Converte linhas em objetos.
         */
        foreach ($matrizArrays as $linhaMatriz) {
            $Disciplina = new Disciplina();

            $Disciplina->setdis_id((int) $linhaMatriz['dis_id']);
            $Disciplina->setdis_nome($linhaMatriz['dis_nome']);

            $Disciplinas[] = $Disciplina;
        }

        /**
         * Retorna lista.
         */
        return $Disciplinas;
    }
}