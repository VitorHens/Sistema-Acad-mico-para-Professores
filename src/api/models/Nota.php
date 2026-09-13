<?php

namespace Api\Models; // Ajuste conforme sua estrutura de pastas

use Api\Models\Matricula;
use JsonSerializable;

class Nota implements JsonSerializable
{
    // Atributos privados
    private ?int $fre_id = null;
    private ?float $fre = null;
    private ?int $nota = null;
    private ?int $matr_id = null;
    private Matricula $Matricula;



    public function __construct()
    {
        // error_log("⬆️  Matricula::__construct()\n");
        $this->Matricula = new Matricula();
    }

    // Getter e Setter para matr_id
    public function getfre_id(): int
    {
        return $this->fre_id;
    }

    public function setfre_id($valor): void
    {
        if (!is_numeric($valor) || intval($valor) != $valor) {
            throw new \Exception("fre_id deve ser um número inteiro.");
        }
        if ($valor <= 0) {
            throw new \Exception("fre_id deve ser um número inteiro positivo.");
        }
        $this->fre_id = intval($valor);
    }

    // Getter e Setter para nota
    public function getfre(): ?float
    {
        return $this->fre;
    }

    public function getnota(): ?int
    {
        return $this->nota;
    }

    public function getmatr_id(): ?int
    {
        return $this->matr_id;
    }
    public function getMatricula(): Matricula
    {
        return $this->Matricula;
    }

    public function setfre($valor): void
    {
        if (!is_numeric($valor)) {
            throw new \Exception("fre(frequencia) deve ser um número.");
        }
        if ($valor < 0 || $valor > 100) {
            throw new \Exception("fre (frequência) deve estar entre 0 e 100.");
        }
        $this->fre = floatval($valor);
    }
    
    public function setnota($valor): void
    {
        if (!is_numeric($valor)) {
            throw new \Exception("nota deve ser um Número.");
        }
        if ($valor < 0 || $valor > 100) {
            throw new \Exception("nota deve estar entre 0 e 100.");
        }
        $this->nota = intval($valor);
    }
    
 public function setmatr_id($valor): void
    {
        if (!is_numeric($valor) || intval($valor) != $valor) {
            throw new \Exception("matr_id deve ser um número inteiro.");
        }
        if ($valor <= 0) {
            throw new \Exception("matr_id deve ser um número inteiro positivo.");
        }
        $this->matr_id = intval($valor);
    }
    public function setMatricula($Matricula): void
    {
        if (!($Matricula instanceof Matricula)) {
            throw new \Exception("Matricula deve ser uma instância válida de Matricula.");
        }
        $this->Matricula = $Matricula;
    }

    public function jsonSerialize(): array
    {
        return [
            'fre_id' => $this->getfre_id(),
            'fre' => $this->getfre(),
            'nota' => $this->getNota(),
            'matr_id' => $this->getmatr_id(),
            'Matricula' => $this->getMatricula() ? $this->getMatricula() : null
        ];
    }
}
