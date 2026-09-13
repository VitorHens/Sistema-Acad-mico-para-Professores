<?php

namespace Api\Services;

use Api\DAO\DisciplinaDAO;
use Api\DAO\ProfessorDAO;

use Api\DAO\TurmaDAO;

use Api\Models\Disciplina;
use Api\Models\Professor;

use Api\Models\Turma;

use Api\Http\ErrorResponse;
use stdClass;

/**
 * Camada de regra de negócio da entidade turma.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class TurmaService
{
    /**
     * DAO de turma.
     *
     * @var TurmaDAO
     */
    private TurmaDAO $TurmaDAO;

    /**
     * DAO de Disciplina.
     *
     * @var DisciplinaDAO
     * @var ProfessorDAO
     */
    private DisciplinaDAO $DisciplinaDAO;
    private ProfessorDAO $ProfessorDAO;

    /**
     * Injeção de dependência.
     *
     * @param TurmaDAO $TurmaDAODependency
     * @param DisciplinaDAO $DisciplinaDAODependency
     * @param ProfessorDAO $ProfessorDAODependency
     */
    public function __construct(
        TurmaDAO $TurmaDAODependency,
        DisciplinaDAO $DisciplinaDAODependency,
        ProfessorDAO $ProfessorDAODependency
    ) {
        error_log("⬆️ TurmaService::__construct()");

        $this->TurmaDAO = $TurmaDAODependency;
        $this->DisciplinaDAO = $DisciplinaDAODependency;
        $this->ProfessorDAO = $ProfessorDAODependency;
    }

    /**
     * Cria novo turma.
     *
     * Regras:
     * - Disciplina informado deve existir.
     * - Email não pode estar duplicado.
     *
     * @param stdClass $jsonTurma
     * @return Turma
     * @throws ErrorResponse
     */
    public function createService(stdClass $jsonTurma): Turma
    {
        error_log("🟣 TurmaService::createService()");

        /**
         * Disciplina informado.
         */

        //echo json_encode($jsonTurma);

        $Disciplina = new Disciplina();
        $Disciplina->setdis_id($jsonTurma->turma->disciplina->dis_id);
        

        /**
         * Verifica se Disciplina existe.
         */
        $DisciplinaExiste = $this->DisciplinaDAO->findById($Disciplina->getdis_id());

        if (!$DisciplinaExiste) {
            throw new ErrorResponse(
                404,
                "Disciplina não encontrado",
                [
                    "message" =>
                        "Não existe Disciplina com id {$Disciplina->getdis_id()}"
                ]
            );
        }

        $Professor = new Professor();
        $Professor->setprof_id($jsonTurma->turma->professor->prof_id);

        /**
         * Verifica se Professor existe.
         */
        $ProfessorExiste = $this->ProfessorDAO->findById($Professor->getprof_id());

        if (!$ProfessorExiste) {
            throw new ErrorResponse(
                404,
                "Professor não encontrado",
                [
                    "message" =>
                        "Não existe Professor com id {$Professor->getprof_id()}"
                ]
            );
        }

        /**
         * Turma.
         */
        $Turma = new Turma();
        
        $Turma->setDisciplina($DisciplinaExiste);
        $Turma->setProfessor($ProfessorExiste);

        /**
         * Salva.
         */
        $idCriado = $this->TurmaDAO->create(
            $Turma
        );

        $Turma->settur_id($idCriado);

        return $Turma;
    }

    /**
     * Lista todos os turmas.
     *
     * @return array
     */
    public function findAll(): array
    {
        error_log("🟣 TurmaService::findAll()");
        return $this->TurmaDAO->findAll();
    }

    /**
     * Busca turma por ID.
     *
     * @param int $tur_id
     * @return Turma
     * @throws ErrorResponse
     */
    public function findByIdService(
        int $tur_id
    ): Turma {
        error_log("🟣 TurmaService::findByIdService()");

        $Turma = $this->TurmaDAO->findById(
            $tur_id
        );

        if (!$Turma) {
            throw new ErrorResponse(
                404,
                "turma não encontrado",
                [
                    "message" =>
                        "Não existe turma com id {$tur_id}"
                ]
            );
        }

        return $Turma;
    }

    /**
     * Atualiza turma existente.
     *
     * Regras:
     * - turma deve existir.
     * - Disciplina informado deve existir.
     *
     * @param int $tur_id
     * @param array $requestBody
     * @return bool
     * @throws ErrorResponse
     */
    public function updateService(
        int $tur_id,
        array $requestBody
    ): bool {
        error_log("🟣 TurmaService::updateService()");

        /**
         * Verifica turma.
         */
        $TurmaExiste =
            $this->TurmaDAO->findById(
                $tur_id
            );

        if (!$TurmaExiste) {
            throw new ErrorResponse(
                404,
                "turma não encontrado",
                [
                    "message" =>
                        "Não existe turma com id {$tur_id}"
                ]
            );
        }

        $jsonTurma =
            $requestBody['turma'];

        /**
         * Verifica Disciplina.
         */
        $Disciplina = $this->DisciplinaDAO->findById(
            $jsonTurma['disciplina']['dis_id']
        );

        if (!$Disciplina) {
            throw new ErrorResponse(
                404,
                "Disciplina não encontrado",
                [
                    "message" =>
                        "Disciplina informado não existe"
                ]
            );
        }

        $Professor = $this->ProfessorDAO->findById(
            $jsonTurma['professor']['prof_id']
        );

        if (!$Professor) {
            throw new ErrorResponse(
                404,
                "Professor não encontrado",
                [
                    "message" =>
                        "Professor informado não existe"
                ]
            );
        }

        /**
         * Monta objeto atualizado.
         */
        $Turma = new Turma();
        $Turma->settur_id(
            $tur_id
        );
        
        $Turma->setDisciplina($Disciplina);
        $Turma->setProfessor($Professor);

        return $this->TurmaDAO->update(
            $Turma
        );
    }

    /**
     * Remove turma existente.
     *
     * Regras:
     * - turma deve existir.
     *
     * @param int $tur_id
     * @return bool
     * @throws ErrorResponse
     */
    public function deleteService(
        int $tur_id
    ): bool {
        error_log("🟣 TurmaService::deleteService()");

        $TurmaExiste =
            $this->TurmaDAO->findById(
                $tur_id
            );

        if (!$TurmaExiste) {
            throw new ErrorResponse(
                404,
                "turma não encontrado",
                [
                    "message" =>
                        "Não existe turma com id {$tur_id}"
                ]
            );
        }

        $Turma = new Turma();
        $Turma->settur_id(
            $tur_id
        );

        return $this->TurmaDAO->delete(
            $Turma
        );
    }

    /**
     * Retorna total de turmas.
     *
     * @return int
     */
    public function countService(): int
    {
        error_log("🟣 TurmaService::countService()");
        return $this->TurmaDAO->count();
    }
}