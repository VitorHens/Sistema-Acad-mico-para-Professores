<?php
namespace Api\Models;
use InvalidArgumentException;
use \JsonSerializable;

/**
 * Representa a entidade Aluno do sistema.
 *
 * Objetivo:
 * - Encapsular os dados de um Aluno.
 * - Garantir integridade dos atributos via getters e setters.
 */
class Aluno implements JsonSerializable
{
    /** @var int Identificador único do Aluno */
    private ?int $alu_id = null;

    /** @var string|null Nome do Aluno */
    private string $alu_nome = "";

    public function __construct()
    {
        // error_log("⬆️  Aluno::__construct()\n");
    }

    /**
     * Getter para alu_id
     * @return int|null Identificador único do Aluno
     */
    public function getalu_id(): ?int
    {
        return $this->alu_id;
    }

    /**
     * Define o ID do Aluno.
     *
     * 🔹 Regra de domínio: garante que o ID seja sempre um número inteiro positivo.
     *
     * @param int $value Número inteiro positivo representando o ID do Aluno.
     * @throws InvalidArgumentException se o valor for inválido.
     */
    public function setalu_id(int $value): void
    {
        if (!is_int($value)) {
            throw new InvalidArgumentException("alu_id deve ser um número inteiro.");
        }

        if ($value <= 0) {
            throw new InvalidArgumentException("alu_id deve ser maior que zero.");
        }

        $this->alu_id = $value;
    }

    /**
     * Getter para alu_nome
     * @return string|null Nome do Aluno
     */
    public function getalu_nome(): ?string
    {
        return $this->alu_nome;
    }

    /**
     * Define o nome do Aluno.
     *
     * 🔹 Regra de domínio: garante que o nome seja sempre uma string não vazia
     * e com pelo menos 3 caracteres e no máximo 64.
     *
     * @param string $value Nome do Aluno.
     * @throws InvalidArgumentException se o valor for inválido.
     */
    public function setalu_nome(string $value): void
    {
        $nome = trim($value);

        if ($nome === '') {
            throw new InvalidArgumentException("alu_nome não pode ser vazio.");
        }

        $len = mb_strlen($nome);

        if ($len < 3) {
            throw new InvalidArgumentException("alu_nome deve ter pelo menos 3 caracteres.");
        }

        if ($len > 64) {
            throw new InvalidArgumentException("alu_nome deve ter no máximo 64 caracteres.");
        }

        $this->alu_nome = $nome;
    }

    /**
     * Implementação da interface JsonSerializable
     *
     * Permite converter a entidade Aluno em formato JSON de forma segura e controlada.
     * Isso garante que apenas os atributos necessários sejam expostos ao cliente.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'alu_id' => $this->getalu_id(),
            'alu_nome' => $this->getalu_nome()
        ];
    }
}
