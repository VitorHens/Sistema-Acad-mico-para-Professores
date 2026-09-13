<?php

namespace Api\Http;

use stdClass;
use DomainException;
use Exception;
use InvalidArgumentException;
use UnexpectedValueException;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;

/**
 * Classe responsável pela geração e validação
 * de tokens JWT.
 */
class MeuTokenJWT
{
    /**
     * Chave secreta utilizada para assinar
     * e validar os tokens.
     *
     * Em produção recomenda-se utilizar
     * variável de ambiente.
     */
    private const KEY = 'x9S4q0v+V0IjvHkG20uAxaHx1ijj+q1HWjHKv+ohxp/oK+77qyXkVj/l4QYHHTF3';

    /**
     * Algoritmo utilizado para assinatura.
     */
    private const ALGORITHM = 'HS256';

    /**
     * Tipo do token.
     */
    private const TYPE = 'JWT';

    /**
     * Payload armazenado após validação.
     */
    private ?stdClass $payload;

    /**
     * Emissor do token (Issuer).
     */
    private string $iss;

    /**
     * Destinatário do token (Audience).
     */
    private string $aud;

    /**
     * Assunto/finalidade do token (Subject).
     */
    private string $sub;

    /**
     * Tempo de vida do token em segundos.
     */
    private int $duration;

    /**
     * Inicializa os atributos da classe.
     */
    public function __construct()
    {
        $this->payload = null;

        $this->iss = 'http://localhost';

        $this->aud = 'http://localhost';

        $this->sub = 'acesso_sistema';

        // 30 dias
        $this->duration = 3600 * 24 * 30;
    }

    /**
     * Gera um token JWT.
     *
     * Espera um objeto contendo:
     *
     * $claims->idFuncionario
     * $claims->name
     * $claims->email
     * $claims->role
     */
    public function gerarToken(stdClass $claims): string
    {
        $headers = [
            'alg' => self::ALGORITHM,
            'typ' => self::TYPE
        ];

        $payload = [

            // Quem gerou o token
            'iss' => $this->iss,

            // Quem deve consumir o token
            'aud' => $this->aud,

            // Finalidade do token
            'sub' => $this->sub,

            // Data de criação
            'iat' => time(),

            // Não válido antes deste momento
            'nbf' => time(),

            // Data de expiração
            'exp' => time() + $this->duration,

            // Identificador único
            'jti' => bin2hex(random_bytes(16)),

            /**
             * Dados públicos do usuário.
             *
             * Lembre-se:
             * JWT NÃO criptografa informações.
             */
            'professor' => [
                'prof_nome' => $claims->prof_nome ?? null,
                'prof_id' => $claims->prof_id ?? null
            ],


        ];

        return JWT::encode($payload, self::KEY, self::ALGORITHM, null, $headers);

    }

    /**
     * Valida um token JWT.
     */
    public function validateToken(string $stringToken): bool
    {
        if (empty($stringToken)) {
            return false;
        }

        /**
         * Remove espaços extras.
         */
        $token = trim($stringToken);

        /**
         * Remove Bearer caso exista.
         *
         * Exemplo:
         * Bearer eyJhbGciOi...
         */
        if (str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        /**
         * Verifica o formato básico:
         * header.payload.signature
         */
        $padrao =
            '/^[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+$/';

        if (preg_match($padrao, $token) !== 1) {
            return false;
        }

        try {

            /**
             * Valida:
             * - assinatura
             * - algoritmo
             * - expiração
             * - nbf
             */
            $payloadValido = JWT::decode($token, new Key(self::KEY, self::ALGORITHM));

            /**
             * Valida ISS.
             */
            if (!isset($payloadValido->iss) || $payloadValido->iss !== $this->iss) {
                return false;
            }

            /**
             * Valida AUD.
             */
            if (!isset($payloadValido->aud) || $payloadValido->aud !== $this->aud) {
                return false;
            }

            /**
             * Valida SUB.
             */
            if (!isset($payloadValido->sub) || $payloadValido->sub !== $this->sub) {
                return false;
            }

            /**
             * Armazena payload validado.
             */
            $this->payload = $payloadValido;

            return true;

        } catch (

            SignatureInvalidException |
            BeforeValidException |
            ExpiredException |
            InvalidArgumentException |
            DomainException |
            UnexpectedValueException |
            Exception $e

        ) {

            return false;
        }
    }

    /**
     * Retorna o payload armazenado.
     */
    public function getPayload(): ?stdClass
    {
        return $this->payload;
    }

    /**
     * Define o payload manualmente.
     */
    public function setPayload(?stdClass $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Limpa o payload armazenado.
     */
    public function limparPayload(): self
    {
        $this->payload = null;

        return $this;
    }

    /**
     * Retorna o emissor configurado.
     */
    public function getIss(): string
    {
        return $this->iss;
    }

    /**
     * Define o emissor.
     */
    public function setIss(string $iss): self
    {
        $this->iss = $iss;

        return $this;
    }

    /**
     * Retorna o destinatário.
     */
    public function getAud(): string
    {
        return $this->aud;
    }

    /**
     * Define o destinatário.
     */
    public function setAud(string $aud): self
    {
        $this->aud = $aud;

        return $this;
    }

    /**
     * Retorna o assunto.
     */
    public function getSub(): string
    {
        return $this->sub;
    }

    /**
     * Define o assunto.
     */
    public function setSub(string $sub): self
    {
        $this->sub = $sub;

        return $this;
    }

    /**
     * Retorna a duração do token.
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * Define a duração do token.
     */
    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}