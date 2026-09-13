<?php

namespace Api\Services;

use Api\Models\Professor;
use Api\DAO\ProfessorDAO;
use Api\Http\ErrorResponse;
use stdClass;
use Api\Http\MeuTokenJWT;
/**
 * Camada de regra de negócio da entidade Professor.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class ProfessorService
{
    /**
     * DAO responsável pelo acesso aos dados.
     *
     * @var ProfessorDAO
     */
    private ProfessorDAO $ProfessorDAO;

    /**
     * Injeção de dependência.
     *
     * @param ProfessorDAO $ProfessorDAODependency
     */
    public function __construct(ProfessorDAO $ProfessorDAODependency)
    {
        error_log("⬆️ ProfessorService::__construct()");
        $this->ProfessorDAO = $ProfessorDAODependency;
    }
    
    /**
     * Realiza o login do funcionário.
     *
     * Fluxo:
     * 1. Valida email e senha recebidos.
     * 2. Solicita ao DAO a autenticação.
     * 3. Se autenticado, gera o JWT.
     * 4. Retorna funcionário e token.
     *
     * @param array $jsonProfessor
     * @return array
     * @throws ErrorResponse
     */
    public function loginService(array $jsonProfessor): array
    {

        error_log("🟣 ProfessorService::loginService()");

        $professor = new Professor();
        $professor->setprof_nome($jsonProfessor['prof_nome']);
        $professor->setprof_id($jsonProfessor['prof_id']);
        /**
         * Solicita autenticação ao DAO.
         */
        $professor = $this->ProfessorDAO->verificarLogin($professor);


        /**
         * Funcionário não autenticado.
         */
        if (!$professor) {

            throw new ErrorResponse(
                401,
                "Usuário ou senha inválidos",
                [
                    "message" =>
                        "Não foi possível autenticar o funcionário"
                ]
            );
        }

        /**
         * Instância responsável pela geração do JWT.
         */
        $jwt = new MeuTokenJWT();

        /**
         * Claims utilizadas na geração do token.
         */
        $claims = new \stdClass();

        $claims->prof_id = $professor->getprof_id();

        $claims->prof_nome = $professor->getprof_nome();

        /**
         * Gera o token JWT.
         */
        $token = $jwt->gerarToken($claims);

        /**
         * Retorna os dados necessários
         * para a autenticação.
         */
        return [
            'professor' => $professor,
            'token' => $token
        ];
    }

    /**
     * Cria um novo Professor.
     *
     * Regras:
     * - Não permite nome duplicado.
     *
     * @param stdClass $objPHP
     * @return Professor
     * @throws ErrorResponse
     */
    public function createService(stdClass $objPHP): Professor
    {
        error_log("🟣 ProfessorService::createService()");

        $Professor = new Professor();
        $Professor->setprof_nome($objPHP->professor->prof_nome);

        /**
         * Verifica duplicidade.
         */
        $resultado = $this->ProfessorDAO->findByField(
            'prof_nome',
            $Professor->getprof_nome()
        );

        if (count($resultado) > 0) {
            throw new ErrorResponse(
                400,
                "Professor já existe",
                [
                    "message" =>
                        "O Professor {$Professor->getprof_nome()} já existe"
                ]
            );
        }

        return $this->ProfessorDAO->create($Professor);
    }

    /**
     * Retorna quantidade total.
     *
     * @return int
     */
    public function countService(): int
    {
        error_log("🟣 ProfessorService::countService()");
        return $this->ProfessorDAO->count();
    }

    /**
     * Lista todos os Professors.
     *
     * @return array
     */
    public function findAllService(): array
    {
        error_log("🟣 ProfessorService::findAllService()");
        return $this->ProfessorDAO->findAll();
    }

    /**
     * Busca Professor por ID.
     *
     * @param int $prof_id
     * @return Professor|null
     */
    public function findByIdService(int $prof_id): ?Professor
    {
        error_log("🟣 ProfessorService::findByIdService()");

        $Professor = new Professor();
        $Professor->setprof_id($prof_id);

        return $this->ProfessorDAO->findById(
            $Professor->getprof_id()
        );
    }

    /**
     * Atualiza Professor existente.
     *
     * Regras:
     * - O Professor precisa existir.
     * - Se não existir, lança erro 404.
     *
     * @param int $prof_id
     * @param string $prof_nome
     * @return bool
     * @throws ErrorResponse
     */
    public function updateService(int $prof_id, string $prof_nome): bool
    {
        error_log("🟣 ProfessorService::updateService()");

        /**
         * Verifica existência.
         */
        $ProfessorExistente = $this->ProfessorDAO->findById($prof_id);

        if (!$ProfessorExistente) {
            throw new ErrorResponse(
                404,
                "Professor não encontrado",
                [
                    "message" =>
                        "Não existe Professor com id {$prof_id}"
                ]
            );
        }

        /**
         * Monta objeto atualizado.
         */
        $Professor = new Professor();
        $Professor->setprof_id($prof_id);
        $Professor->setprof_nome($prof_nome);

        return $this->ProfessorDAO->update($Professor);
    }

    /**
     * Remove Professor existente.
     *
     * Regras:
     * - O Professor precisa existir.
     * - Se não existir, lança erro 404.
     *
     * @param int $prof_id
     * @return bool
     * @throws ErrorResponse
     */
    public function deleteService(int $prof_id): bool
    {
        error_log("🟣 ProfessorService::deleteService()");

        /**
         * Verifica existência.
         */
        $ProfessorExistente = $this->ProfessorDAO->findById($prof_id);

        if (!$ProfessorExistente) {
            throw new ErrorResponse(
                404,
                "Professor não encontrado",
                [
                    "message" =>
                        "Não existe Professor com id {$prof_id}"
                ]
            );
        }

        /**
         * Monta objeto para exclusão.
         */
        $Professor = new Professor();
        $Professor->setprof_id($prof_id);

        return $this->ProfessorDAO->delete($Professor);
    }
}