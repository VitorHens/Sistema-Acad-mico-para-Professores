<?php
namespace Api\Models;
use InvalidArgumentException;
use \JsonSerializable;

/**
 * Representa a entidade Professor do sistema.
 *
 * Objetivo:
 * - Encapsular os dados de um Professor.
 * - Garantir integridade dos atributos via getters e setters.
 */
class Professor implements JsonSerializable
{
    /** @var int Identificador único do Professor */
    private ?int $prof_id = null;

    /** @var string|null Nome do Professor */
    private string $prof_nome = "";

    public function __construct()
    {
        // error_log("⬆️  Professor::__construct()\n");
    }

    /**
     * Getter para prof_id
     * @return int|null Identificador único do Professor
     */
    public function getprof_id(): ?int
    {
        return $this->prof_id;
    }

    /**
     * Define o ID do Professor.
     *
     * 🔹 Regra de domínio: garante que o ID seja sempre um número inteiro positivo.
     *
     * @param int $vprofe Número inteiro positivo representando o ID do Professor.
     * @throws InvalidArgumentException se o valor for inválido.
     */
    public function setprof_id(int $vprofe): void
    {
        if (!is_int($vprofe)) {
            throw new InvalidArgumentException("prof_id deve ser um número inteiro.");
        }

        if ($vprofe <= 0) {
            throw new InvalidArgumentException("prof_id deve ser maior que zero.");
        }

        $this->prof_id = $vprofe;
    }

    /**
     * Getter para prof_nome
     * @return string|null Nome do Professor
     */
    public function getprof_nome(): ?string
    {
        return $this->prof_nome;
    }

    /**
     * Define o nome do Professor.
     *
     * 🔹 Regra de domínio: garante que o nome seja sempre uma string não vazia
     * e com pelo menos 3 caracteres e no máximo 64.
     *
     * @param string $vprofe Nome do Professor.
     * @throws InvalidArgumentException se o valor for inválido.
     */
    public function setprof_nome(string $vprofe): void
    {
        $nome = trim($vprofe);

        if ($nome === '') {
            throw new InvalidArgumentException("prof_nome não pode ser vazio.");
        }

        $len = mb_strlen($nome);

        if ($len < 3) {
            throw new InvalidArgumentException("prof_nome deve ter pelo menos 3 caracteres.");
        }

        if ($len > 64) {
            throw new InvalidArgumentException("prof_nome deve ter no máximo 64 caracteres.");
        }

        $this->prof_nome = $nome;
    }

    /**
     * Implementação da interface JsonSerializable
     *
     * Permite converter a entidade Professor em formato JSON de forma segura e controlada.
     * Isso garante que apenas os atributos necessários sejam expostos ao cliente.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'prof_id' => $this->getprof_id(),
            'prof_nome' => $this->getprof_nome()
        ];
    }
}
