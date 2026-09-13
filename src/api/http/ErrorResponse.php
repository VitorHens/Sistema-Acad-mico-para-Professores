<?php

namespace Api\Http;

use Exception;

/**
 * Classe personalizada de erro para a aplicação.
 *
 * Permite incluir:
 * - Código HTTP ($httpCode)
 * - Informações adicionais sobre o erro ($error)
 *
 * Pode ser utilizada em middlewares ou serviços para padronizar respostas de erro.
 */
class ErrorResponse extends Exception
{
    /** @var int Código HTTP a ser retornado */
    private int $httpCode;

    /** @var mixed Detalhes adicionais do erro */
    private $error;

    /** @var string Nome da exceção */
    private string $name;

    /**
     * Construtor da classe ErrorResponse
     *
     * @param int $httpCode Código de status HTTP (ex: 400, 404, 500)
     * @param string $message Mensagem de erro descritiva
     * @param mixed $error Objeto ou string adicional com detalhes do erro (opcional)
     */
    public function __construct(int $httpCode, string $message, $error = null)
    {
        parent::__construct($message);
        $this->name = "ErrorResponse";
        $this->httpCode = $httpCode;
        $this->error = $error;
    }

    /**
     * Retorna o código HTTP associado ao erro.
     *
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * Retorna informações adicionais sobre o erro.
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Retorna o nome da exceção.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
