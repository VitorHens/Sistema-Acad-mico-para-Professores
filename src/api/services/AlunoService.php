<?php

namespace Api\Services;

use Api\Models\Aluno;
use Api\DAO\AlunoDAO;
use Api\Http\ErrorResponse;
use stdClass;

/**
 * Camada de regra de negócio da entidade Aluno.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class AlunoService
{
    /**
     * DAO responsável pelo acesso aos dados.
     *
     * @var AlunoDAO
     */
    private AlunoDAO $AlunoDAO;

    /**
     * Injeção de dependência.
     *
     * @param AlunoDAO $AlunoDAODependency
     */
    public function __construct(AlunoDAO $AlunoDAODependency)
    {
        error_log("⬆️ AlunoService::__construct()");
        $this->AlunoDAO = $AlunoDAODependency;
    }

    /**
     * Cria um novo Aluno.
     *
     * Regras:
     * - Não permite nome duplicado.
     *
     * @param stdClass $objPHP
     * @return Aluno
     * @throws ErrorResponse
     */
    public function createService(stdClass $objPHP): Aluno
    {
        error_log("🟣 AlunoService::createService()");

        $Aluno = new Aluno();
        $Aluno->setalu_nome($objPHP->aluno->alu_nome);

        /**
         * Verifica duplicidade.
         */
        $resultado = $this->AlunoDAO->findByField(
            'alu_nome',
            $Aluno->getalu_nome()
        );

        if (count($resultado) > 0) {
            throw new ErrorResponse(
                400,
                "Aluno já existe",
                [
                    "message" =>
                        "O Aluno {$Aluno->getalu_nome()} já existe"
                ]
            );
        }

        return $this->AlunoDAO->create($Aluno);
    }

    /**
     * Retorna quantidade total.
     *
     * @return int
     */
    public function countService(): int
    {
        error_log("🟣 AlunoService::countService()");
        return $this->AlunoDAO->count();
    }

    /**
     * Lista todos os Alunos.
     *
     * @return array
     */
    public function findAllService(): array
    {
        error_log("🟣 AlunoService::findAllService()");
        return $this->AlunoDAO->findAll();
    }

    /**
     * Busca Aluno por ID.
     *
     * @param int $alu_id
     * @return Aluno|null
     */
    public function findByIdService(int $alu_id): ?Aluno
    {
        error_log("🟣 AlunoService::findByIdService()");

        $Aluno = new Aluno();
        $Aluno->setalu_id($alu_id);

        return $this->AlunoDAO->findById(
            $Aluno->getalu_id()
        );
    }

    /**
     * Atualiza Aluno existente.
     *
     * Regras:
     * - O Aluno precisa existir.
     * - Se não existir, lança erro 404.
     *
     * @param int $alu_id
     * @param string $alu_nome
     * @return bool
     * @throws ErrorResponse
     */
    public function updateService(int $alu_id, string $alu_nome): bool
    {
        error_log("🟣 AlunoService::updateService()");

        /**
         * Verifica existência.
         */
        $AlunoExistente = $this->AlunoDAO->findById($alu_id);

        if (!$AlunoExistente) {
            throw new ErrorResponse(
                404,
                "Aluno não encontrado",
                [
                    "message" =>
                        "Não existe Aluno com id {$alu_id}"
                ]
            );
        }

        /**
         * Monta objeto atualizado.
         */
        $Aluno = new Aluno();
        $Aluno->setalu_id($alu_id);
        $Aluno->setalu_nome($alu_nome);

        return $this->AlunoDAO->update($Aluno);
    }

    /**
     * Remove Aluno existente.
     *
     * Regras:
     * - O Aluno precisa existir.
     * - Se não existir, lança erro 404.
     *
     * @param int $alu_id
     * @return bool
     * @throws ErrorResponse
     */
    public function deleteService(int $alu_id): bool
    {
        error_log("🟣 AlunoService::deleteService()");

        /**
         * Verifica existência.
         */
        $AlunoExistente = $this->AlunoDAO->findById($alu_id);

        if (!$AlunoExistente) {
            throw new ErrorResponse(
                404,
                "Aluno não encontrado",
                [
                    "message" =>
                        "Não existe Aluno com id {$alu_id}"
                ]
            );
        }

        /**
         * Monta objeto para exclusão.
         */
        $Aluno = new Aluno();
        $Aluno->setalu_id($alu_id);

        return $this->AlunoDAO->delete($Aluno);
    }
}