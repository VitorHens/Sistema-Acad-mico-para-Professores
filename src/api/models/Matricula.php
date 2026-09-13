<?php

namespace Api\Models; // Ajuste conforme sua estrutura de pastas

use Api\Models\Turma;
use Api\Models\Aluno;
use Api\Models\Disciplina;
use JsonSerializable;

class Matricula implements JsonSerializable
{
    // Atributos privados
    private ?int $matr_id = null;

    private Turma $Turma;
    private Aluno $Aluno;
    



    public function __construct()
    {
        // error_log("⬆️  Matricula::__construct()\n");
        $this->Turma = new Turma();
        $this->Aluno = new Aluno();
        
    }

    // Getter e Setter para matr_id
    public function getmatr_id(): int
    {
        return $this->matr_id;
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

    // Getter e Setter para Turma
    public function getTurma(): Turma
    {
        return $this->Turma;
    }

    public function getAluno(): Aluno
    {
        return $this->Aluno;
    }

    

    public function setTurma($Turma): void
    {
        if (!($Turma instanceof Turma)) {
            throw new \Exception("Turma deve ser uma instância válida de Turma.");
        }
        $this->Turma = $Turma;
    }
    public function setAluno($Aluno): void
    {
        if (!($Aluno instanceof Aluno)) {
            throw new \Exception("Aluno deve ser uma instância válida de Aluno.");
        }
        $this->Aluno = $Aluno;
    }

    

    public function jsonSerialize(): array
    {
        return [
            'matr_id' => $this->getmatr_id(),
            'Turma' => $this->getTurma() ? $this->getTurma() : null,
            'Aluno' => $this->getAluno() ? $this->getAluno() : null,
            
        ];
    }
}
