<?php

namespace Api\Services;

use Api\DAO\NotaDAO;
use Api\DAO\MatriculaDAO;

use Api\Models\Matricula;
use Api\Models\Nota;

use Api\Http\ErrorResponse;
use stdClass;

/**
 * Camada de regra de negócio da entidade Nota.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class NotaService
{
    private NotaDAO $NotaDAO;
    private MatriculaDAO $MatriculaDAO;

    public function __construct(
        NotaDAO $NotaDAODependency,
        MatriculaDAO $MatriculaDAODependency,
    ) {
        error_log("⬆️ NotaService::__construct()");
        $this->NotaDAO = $NotaDAODependency;
        $this->MatriculaDAO = $MatriculaDAODependency;
    }

    /**
     * Cria novo Nota.
     */
    public function createService(stdClass $jsonNota): Nota
    {
        error_log("🟣 NotaService::createService()");

        $matr_id = $jsonNota->nota->matr_id ?? null;
        
        if (!$matr_id) {
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                ["message" => "O campo 'matr_id' é obrigatório!"]
            );
        }

        $MatriculaExiste = $this->MatriculaDAO->findById($matr_id);

        if (!$MatriculaExiste) {
            throw new ErrorResponse(
                404,
                "Matrícula não encontrada",
                ["message" => "Não existe Matrícula com id {$matr_id}"]
            );
        }

        $Nota = new Nota();
        $Nota->setfre($jsonNota->nota->fre ?? 0);           // setfre (minúsculo)
        $Nota->setNota($jsonNota->nota->nota ?? 0);         // setNota
        $Nota->setmatr_id($matr_id);                         // setmatr_id
        $Nota->setMatricula($MatriculaExiste);               // setMatricula

        $idCriado = $this->NotaDAO->create($Nota);
        $Nota->setfre_id($idCriado);                         // setfre_id
        
        return $Nota;
    }

    /**
     * Lista todos os Notas.
     */
    public function findAll(): array
    {
        error_log("🟣 NotaService::findAll()");
        return $this->NotaDAO->findAll();
    }

    /**
     * Busca Nota por ID.
     */
    public function findByIdService(int $fre_id): Nota
    {
        error_log("🟣 NotaService::findByIdService()");

        $Nota = $this->NotaDAO->findById($fre_id);

        if (!$Nota) {
            throw new ErrorResponse(
                404,
                "Nota não encontrada",
                ["message" => "Não existe Nota com id {$fre_id}"]
            );
        }

        return $Nota;
    }

    /**
     * Busca notas por matrícula.
     */
    public function findByMatriculaService(int $matr_id): array
    {
        error_log("🟣 NotaService::findByMatriculaService()");

        $MatriculaExiste = $this->MatriculaDAO->findById($matr_id);

        if (!$MatriculaExiste) {
            throw new ErrorResponse(
                404,
                "Matrícula não encontrada",
                ["message" => "Não existe Matrícula com id {$matr_id}"]
            );
        }

        return $this->NotaDAO->findByMatriculaId($matr_id);
    }

    /**
     * Atualiza Nota existente.
     */
    public function updateService(int $fre_id, array $requestBody): bool
    {

        error_log("🟣 NotaService::updateService()");

        $NotaExiste = $this->NotaDAO->findById($fre_id);

        if (!$NotaExiste) {
            throw new ErrorResponse(
                404,
                "Nota não encontrada",
                ["message" => "Não existe Nota com id {$fre_id}"]
            );
        }

        $jsonNota = $requestBody['Nota'] ?? $requestBody['nota'] ?? null;

        if (!$jsonNota) {
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                ["message" => "O campo 'nota' é obrigatório!"]
            );
        }

        // Verifica se a matrícula existe (se foi fornecida)
        if (isset($jsonNota['matr_id'])) {
            $MatriculaExiste = $this->MatriculaDAO->findById($jsonNota['matr_id']);

            if (!$MatriculaExiste) {
                throw new ErrorResponse(
                    404,
                    "Matrícula não encontrada",
                    ["message" => "Não existe Matrícula com id {$jsonNota['matr_id']}"]
                );
            }
        }

        // Monta objeto atualizado
        $Nota = new Nota();
        $Nota->setfre_id($fre_id);                              // setfre_id
        $Nota->setfre($jsonNota['fre'] ?? $NotaExiste->getfre());     // setfre
        $Nota->setNota($jsonNota['nota'] ?? $NotaExiste->getNota());   // setNota
        $Matricula = $this->MatriculaDAO->findById(
            $jsonNota['matr_id'] ?? $NotaExiste->getMatricula()->getmatr_id()
        );
        $Nota->setMatricula($Matricula);

        return $this->NotaDAO->update($Nota);
    }

    /**
     * Remove Nota existente.
     */
    public function deleteService(int $fre_id): bool
    {
        error_log("🟣 NotaService::deleteService()");

        $NotaExiste = $this->NotaDAO->findById($fre_id);

        if (!$NotaExiste) {
            throw new ErrorResponse(
                404,
                "Nota não encontrada",
                ["message" => "Não existe Nota com id {$fre_id}"]
            );
        }

        $Nota = new Nota();
        $Nota->setfre_id($fre_id);  // setfre_id

        return $this->NotaDAO->delete($Nota);
    }

    /**
     * Retorna total de Notas.
     */
    public function countService(): int
    {
        error_log("🟣 NotaService::countService()");
        return $this->NotaDAO->count();
    }
}