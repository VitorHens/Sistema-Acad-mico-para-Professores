<?php

namespace Api\Services;

use Api\DAO\TurmaDAO;
use Api\DAO\AlunoDAO;
use Api\DAO\NotaDAO;
use Api\DAO\MatriculaDAO;

use Api\Models\Turma;
use Api\Models\Aluno;

use Api\Models\Matricula;

use Api\Http\ErrorResponse;
use stdClass;

/**
 * Camada de regra de negócio da entidade Matricula.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class MatriculaService
{
    /**
     * DAO de Matricula.
     *
     * @var MatriculaDAO
     */
    private MatriculaDAO $MatriculaDAO;

    /**
     * DAO de Turma.
     *
     * @var TurmaDAO
     * @var AlunoDAO
     * @var NotaDAO
     */
    private TurmaDAO $TurmaDAO;

    private AlunoDAO $AlunoDAO;

    private NotaDAO $NotaDAO;
    /**
     * Injeção de dependência.
     *
     * @param MatriculaDAO $MatriculaDAODependency
     * @param TurmaDAO $TurmaDAODependency
     * @param AlunoDAO $AlunoDAODependency
     * @param NotaDAO $NotaDAODependency
     */
    public function __construct(
        MatriculaDAO $MatriculaDAODependency,
        TurmaDAO $TurmaDAODependency,
        AlunoDAO $AlunoDAODependency,
        NotaDAO $NotaDAODependency,
    ) {
        error_log("⬆️ MatriculaService::__construct()");

        $this->MatriculaDAO = $MatriculaDAODependency;
        $this->TurmaDAO = $TurmaDAODependency;
        $this->AlunoDAO = $AlunoDAODependency;
        $this->NotaDAO = $NotaDAODependency;
    }

    /**
     * Cria novo Matricula.
     *
     * Regras:
     * - Turma informado deve existir.
     * - Email não pode estar duplicado.
     *
     * @param stdClass $jsonMatricula
     * @return Matricula
     * @throws ErrorResponse
     */
    public function createService(stdClass $jsonMatricula): Matricula
    {
        error_log("🟣 MatriculaService::createService()");

        /**
         * Turma informado.
         */

        //echo json_encode($jsonMatricula);

        $Turma = new Turma();
        $Turma->settur_id($jsonMatricula->matricula->turma->tur_id);
        

        /**
         * Verifica se Turma existe.
         */
        $TurmaExiste = $this->TurmaDAO->findById($Turma->gettur_id());

        if (!$TurmaExiste) {
            throw new ErrorResponse(
                404,
                "Turma não encontrado",
                [
                    "message" =>
                        "Não existe Turma com id {$Turma->gettur_id()}"
                ]
            );
        }

        $Aluno = new Aluno();
        $Aluno->setalu_id($jsonMatricula->matricula->aluno->alu_id);

        /**
         * Verifica se Aluno existe.
         */
        $AlunoExiste = $this->AlunoDAO->findById($Aluno->getalu_id());

        if (!$AlunoExiste) {
            throw new ErrorResponse(
                404,
                "Aluno não encontrado",
                [
                    "message" =>
                        "Não existe Aluno com id {$Aluno->getalu_id()}"
                ]
            );
        }      
        /**
         * Matricula.
         */
        $Matricula = new Matricula();
        
        $Matricula->setTurma($TurmaExiste);
        $Matricula->setAluno($AlunoExiste);

        /**
         * Salva.
         */
        $idCriado = $this->MatriculaDAO->create(
            $Matricula
        );

        $Matricula->setmatr_id($idCriado);

        return $Matricula;
    }

    /**
     * Lista todos os Matriculas.
     *
     * @return array
     */
    public function findAll(): array
    {
        error_log("🟣 MatriculaService::findAll()");
        return $this->MatriculaDAO->findAll();
    }

    /**
     * Busca Matricula por ID.
     *
     * @param int $matr_id
     * @return Matricula
     * @throws ErrorResponse
     */
    public function findByIdService(
        int $matr_id
    ): Matricula {
        error_log("🟣 MatriculaService::findByIdService()");

        $Matricula = $this->MatriculaDAO->findById(
            $matr_id
        );

        if (!$Matricula) {
            throw new ErrorResponse(
                404,
                "Matricula não encontrado",
                [
                    "message" =>
                        "Não existe Matricula com id {$matr_id}"
                ]
            );
        }

        return $Matricula;
    }

    /**
     * Atualiza Matricula existente.
     *
     * Regras:
     * - Matricula deve existir.
     * - Turma informado deve existir.
     *
     * @param int $matr_id
     * @param array $requestBody
     * @return bool
     * @throws ErrorResponse
     */
    public function updateService(
        int $matr_id,
        array $requestBody
    ): bool {
        error_log("🟣 MatriculaService::updateService()");

        /**
         * Verifica Matricula.
         */
        $MatriculaExiste =
            $this->MatriculaDAO->findById(
                $matr_id
            );

        if (!$MatriculaExiste) {
            throw new ErrorResponse(
                404,
                "Matricula não encontrado",
                [
                    "message" =>
                        "Não existe Matricula com id {$matr_id}"
                ]
            );
        }

        $jsonMatricula =
            $requestBody['matricula'];

        /**
         * Verifica Turma.
         */
        $Turma = $this->TurmaDAO->findById(
            $jsonMatricula['turma']['tur_id']
        );

        if (!$Turma) {
            throw new ErrorResponse(
                404,
                "Turma não encontrado",
                [
                    "message" =>
                        "Turma informado não existe"
                ]
            );
        }

        $Aluno = $this->AlunoDAO->findById(
            $jsonMatricula['aluno']['alu_id']
        );

        if (!$Aluno) {
            throw new ErrorResponse(
                404,
                "Aluno não encontrado",
                [
                    "message" =>
                        "Aluno informado não existe"
                ]
            );
        }

        /**
         * Monta objeto atualizado.
         */
        $Matricula = new Matricula();
        $Matricula->setmatr_id(
            $matr_id
        );
        
        $Matricula->setTurma($Turma);
        $Matricula->setAluno($Aluno);

        return $this->MatriculaDAO->update(
            $Matricula
        );
    }

    /**
     * Remove Matricula existente.
     *
     * Regras:
     * - Matricula deve existir.
     *
     * @param int $matr_id
     * @return bool
     * @throws ErrorResponse
     */
    public function deleteService(
        int $matr_id
    ): bool {
        error_log("🟣 MatriculaService::deleteService()");

        $MatriculaExiste =
            $this->MatriculaDAO->findById(
                $matr_id
            );

        if (!$MatriculaExiste) {
            throw new ErrorResponse(
                404,
                "Matricula não encontrado",
                [
                    "message" =>
                        "Não existe Matricula com id {$matr_id}"
                ]
            );
        }

        $this->NotaDAO->deleteByMatriculaId($matr_id);

        $Matricula = new Matricula();
        $Matricula->setmatr_id(
            $matr_id
        );

        return $this->MatriculaDAO->delete(
            $Matricula
        );
    }

    /**
     * Retorna total de Matriculas.
     *
     * @return int
     */
    public function countService(): int
    {
        error_log("🟣 MatriculaService::countService()");
        return $this->MatriculaDAO->count();
    }
}