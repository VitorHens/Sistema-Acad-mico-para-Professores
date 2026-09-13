<?php

namespace Api\Models; // Ajuste conforme sua estrutura de pastas

use Api\Models\Disciplina;
use Api\Models\Professor;
use JsonSerializable;

class Turma implements JsonSerializable
{
    // Atributos privados
    private ?int $tur_id = null;

    private Disciplina $Disciplina;
    private Professor $Professor;



    public function __construct()
    {
        // error_log("⬆️  Turma::__construct()\n");
        $this->Disciplina = new Disciplina();
        $this->Professor = new Professor();
    }

    // Getter e Setter para tur_id
    public function gettur_id(): ?int
    {
        return $this->tur_id ?? null;
    }

    public function settur_id($valor): void
    {
        if (!is_numeric($valor) || intval($valor) != $valor) {
            throw new \Exception("tur_id deve ser um número inteiro.");
        }
        if ($valor <= 0) {
            throw new \Exception("tur_id deve ser um número inteiro positivo.");
        }
        $this->tur_id = intval($valor);
    }

    // Getter e Setter para Disciplina
    public function getDisciplina(): Disciplina
    {
        return $this->Disciplina;
    }

    public function getProfessor(): Professor
    {
        return $this->Professor;
    }

    public function setDisciplina($Disciplina): void
    {
        if (!($Disciplina instanceof Disciplina)) {
            throw new \Exception("Disciplina deve ser uma instância válida de Disciplina.");
        }
        $this->Disciplina = $Disciplina;
    }
    public function setProfessor($Professor): void
    {
        if (!($Professor instanceof Professor)) {
            throw new \Exception("Professor deve ser uma instância válida de Professor.");
        }
        $this->Professor = $Professor;
    }

    public function jsonSerialize(): array
    {
        return [
            'tur_id' => $this->gettur_id(),
            'Disciplina' => $this->getDisciplina() ? $this->getDisciplina() : null,
            'Professor' => $this->getProfessor() ? $this->getProfessor() : null
        ];
    }
}
