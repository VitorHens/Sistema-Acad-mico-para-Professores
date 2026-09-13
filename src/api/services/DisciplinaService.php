<?php

namespace Api\Services;

use Api\Models\Disciplina;
use Api\DAO\DisciplinaDAO;
use Api\Http\ErrorResponse;
use stdClass;

/**
 * Camada de regra de negócio da entidade Disciplina.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class DisciplinaService
{
    /**
     * DAO responsável pelo acesso aos dados.
     *
     * @var DisciplinaDAO
     */
    private DisciplinaDAO $DisciplinaDAO;

    /**
     * Injeção de dependência.
     *
     * @param DisciplinaDAO $DisciplinaDAODependency
     */
    public function __construct(DisciplinaDAO $DisciplinaDAODependency)
    {
        error_log("⬆️ DisciplinaService::__construct()");
        $this->DisciplinaDAO = $DisciplinaDAODependency;
    }

    /**
     * Cria um novo Disciplina.
     *
     * Regras:
     * - Não permite nome duplicado.
     *
     * @param stdClass $objPHP
     * @return Disciplina
     * @throws ErrorResponse
     */
    public function createService(stdClass $objPHP): Disciplina
    {
        error_log("🟣 DisciplinaService::createService()");

        $Disciplina = new Disciplina();
        $Disciplina->setdis_nome($objPHP->disciplina->dis_nome);

        /**
         * Verifica duplicidade.
         */
        $resultado = $this->DisciplinaDAO->findByField(
            'dis_nome',
            $Disciplina->getdis_nome()
        );

        if (count($resultado) > 0) {
            throw new ErrorResponse(
                400,
                "Disciplina já existe",
                [
                    "message" =>
                        "O Disciplina {$Disciplina->getdis_nome()} já existe"
                ]
            );
        }

        return $this->DisciplinaDAO->create($Disciplina);
    }

    /**
     * Retorna quantidade total.
     *
     * @return int
     */
    public function countService(): int
    {
        error_log("🟣 DisciplinaService::countService()");
        return $this->DisciplinaDAO->count();
    }

    /**
     * Lista todos os Disciplinas.
     *
     * @return array
     */
    public function findAllService(): array
    {
        error_log("🟣 DisciplinaService::findAllService()");
        return $this->DisciplinaDAO->findAll();
    }

    /**
     * Busca Disciplina por ID.
     *
     * @param int $dis_id
     * @return Disciplina|null
     */
    public function findByIdService(int $dis_id): ?Disciplina
    {
        error_log("🟣 DisciplinaService::findByIdService()");

        $Disciplina = new Disciplina();
        $Disciplina->setdis_id($dis_id);

        return $this->DisciplinaDAO->findById(
            $Disciplina->getdis_id()
        );
    }

    /**
     * Atualiza Disciplina existente.
     *
     * Regras:
     * - O Disciplina precisa existir.
     * - Se não existir, lança erro 404.
     *
     * @param int $dis_id
     * @param string $dis_nome
     * @return bool
     * @throws ErrorResponse
     */
    public function updateService(int $dis_id, string $dis_nome): bool
    {
        error_log("🟣 DisciplinaService::updateService()");

        /**
         * Verifica existência.
         */
        $DisciplinaExistente = $this->DisciplinaDAO->findById($dis_id);

        if (!$DisciplinaExistente) {
            throw new ErrorResponse(
                404,
                "Disciplina não encontrado",
                [
                    "message" =>
                        "Não existe Disciplina com id {$dis_id}"
                ]
            );
        }

        /**
         * Monta objeto atualizado.
         */
        $Disciplina = new Disciplina();
        $Disciplina->setdis_id($dis_id);
        $Disciplina->setdis_nome($dis_nome);

        return $this->DisciplinaDAO->update($Disciplina);
    }

    /**
     * Remove Disciplina existente.
     *
     * Regras:
     * - O Disciplina precisa existir.
     * - Se não existir, lança erro 404.
     *
     * @param int $dis_id
     * @return bool
     * @throws ErrorResponse
     */
    public function deleteService(int $dis_id): bool
    {
        error_log("🟣 DisciplinaService::deleteService()");

        /**
         * Verifica existência.
         */
        $DisciplinaExistente = $this->DisciplinaDAO->findById($dis_id);

        if (!$DisciplinaExistente) {
            throw new ErrorResponse(
                404,
                "Disciplina não encontrado",
                [
                    "message" =>
                        "Não existe Disciplina com id {$dis_id}"
                ]
            );
        }

        /**
         * Monta objeto para exclusão.
         */
        $Disciplina = new Disciplina();
        $Disciplina->setdis_id($dis_id);

        return $this->DisciplinaDAO->delete($Disciplina);
    }
}