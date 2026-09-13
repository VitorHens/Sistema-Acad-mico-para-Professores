<?php
namespace Api\Models;
use InvalidArgumentException;
use \JsonSerializable;

/**
 * Representa a entidade Disciplina do sistema.
 *
 * Objetivo:
 * - Encapsular os dados de um Disciplina.
 * - Garantir integridade dos atributos via getters e setters.
 */
class Disciplina implements JsonSerializable
{
    /** @var int Identificador único do Disciplina */
    private ?int $dis_id = null;

    /** @var string|null Nome do Disciplina */
    private string $dis_nome = "";

    public function __construct()
    {
        $this->dis_id = null;
        $this->dis_nome = "";
        // error_log("⬆️  Disciplina::__construct()\n");
    }

    /**
     * Getter para dis_id
     * @return int|null Identificador único do Disciplina
     */
    public function getdis_id(): ?int
    {
        return $this->dis_id;
    }

    /**
     * Define o ID do Disciplina.
     *
     * 🔹 Regra de domínio: garante que o ID seja sempre um número inteiro positivo.
     *
     * @param int $value Número inteiro positivo representando o ID do Disciplina.
     * @throws InvalidArgumentException se o valor for inválido.
     */
    public function setdis_id(int $value): void
    {
        if (!is_int($value)) {
            throw new InvalidArgumentException("dis_id deve ser um número inteiro.");
        }

        if ($value <= 0) {
            throw new InvalidArgumentException("dis_id deve ser maior que zero.");
        }

        $this->dis_id = $value;
    }

    /**
     * Getter para dis_nome
     * @return string|null Nome do Disciplina
     */
    public function getdis_nome(): ?string
    {
        return $this->dis_nome;
    }

    /**
     * Define o nome do Disciplina.
     *
     * 🔹 Regra de domínio: garante que o nome seja sempre uma string não vazia
     * e com pelo menos 3 caracteres e no máximo 64.
     *
     * @param string $value Nome do Disciplina.
     * @throws InvalidArgumentException se o valor for inválido.
     */
    public function setdis_nome(string $value): void
    {
        $nome = trim($value);

        if ($nome === '') {
            throw new InvalidArgumentException("dis_nome não pode ser vazio.");
        }

        $len = mb_strlen($nome);

        if ($len < 3) {
            throw new InvalidArgumentException("dis_nome deve ter pelo menos 3 caracteres.");
        }

        if ($len > 64) {
            throw new InvalidArgumentException("dis_nome deve ter no máximo 64 caracteres.");
        }

        $this->dis_nome = $nome;
    }

    /**
     * Implementação da interface JsonSerializable
     *
     * Permite converter a entidade Disciplina em formato JSON de forma segura e controlada.
     * Isso garante que apenas os atributos necessários sejam expostos ao cliente.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'dis_id' => $this->getdis_id(),
            'dis_nome' => $this->getdis_nome()
        ];
    }
}
