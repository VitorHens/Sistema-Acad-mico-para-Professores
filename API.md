# 📡 Documentação da API — Sistema Acadêmico

Documentação dos endpoints disponíveis na API do **Sistema Acadêmico para Professores**.

> A API permite o gerenciamento das entidades acadêmicas utilizadas pelo sistema, incluindo alunos, professores, disciplinas, turmas, matrículas, notas e frequência.

---

## 🌐 URL Base

```text
http://localhost:8080
```

> Para rotas protegidas, envie o token JWT no cabeçalho de autorização conforme a configuração da aplicação.

---

## 👤 Alunos

### Listar alunos

```http
GET /alunos
```

### Buscar aluno por ID

```http
GET /alunos/{alu_id}
```

### Criar aluno

```http
POST /alunos
```

Exemplo de corpo:

```json
{
  "aluno": {
    "alu_nome": "Ana"
  }
}
```

### Atualizar aluno

```http
PUT /alunos/{alu_id}
```

### Excluir aluno

```http
DELETE /alunos/{alu_id}
```

### Contar alunos

```http
GET /alunos/count
```

---

## 👨‍🏫 Professores

### Listar professores

```http
GET /professores
```

### Buscar professor por ID

```http
GET /professores/{prof_id}
```

### Criar professor

```http
POST /professores
```

Exemplo de corpo:

```json
{
  "professor": {
    "prof_nome": "Gabriel"
  }
}
```

### Atualizar professor

```http
PUT /professores/{prof_id}
```

### Excluir professor

```http
DELETE /professores/{prof_id}
```

### Contar professores

```http
GET /professores/count
```

---

## 📚 Disciplinas

### Listar disciplinas

```http
GET /disciplinas
```

### Buscar disciplina por ID

```http
GET /disciplinas/{dis_id}
```

### Criar disciplina

```http
POST /disciplinas
```

Exemplo de corpo:

```json
{
  "disciplina": {
    "dis_nome": "Programação Web"
  }
}
```

### Atualizar disciplina

```http
PUT /disciplinas/{dis_id}
```

### Excluir disciplina

```http
DELETE /disciplinas/{dis_id}
```

### Contar disciplinas

```http
GET /disciplinas/count
```

---

## 🏫 Turmas

### Listar turmas

```http
GET /turmas
```

### Buscar turma por ID

```http
GET /turmas/{tur_id}
```

### Criar turma

```http
POST /turmas
```

Exemplo de corpo:

```json
{
  "turma": {
    "disciplina": {
      "dis_id": 5
    },
    "professor": {
      "prof_id": 3
    }
  }
}
```

### Atualizar turma

```http
PUT /turmas/{tur_id}
```

### Excluir turma

```http
DELETE /turmas/{tur_id}
```

### Contar turmas

```http
GET /turmas/count
```

---

## 📝 Matrículas

A API também possui recursos de gerenciamento de matrículas, responsáveis por relacionar alunos às turmas.

Use as rotas definidas no projeto para listar, cadastrar, atualizar e excluir matrículas.

---

## 📊 Notas e frequência

O sistema possui recursos para cadastro e gerenciamento de informações acadêmicas relacionadas a notas e frequência.

---

## 🔐 Autenticação

O sistema utiliza **JWT (JSON Web Token)** para autenticação e proteção dos recursos da API. Após o login, o token retornado deve ser utilizado nas requisições protegidas.

---

## 📌 Observação

Esta documentação apresenta as principais rotas e a organização geral da API. Para conferir todos os campos esperados e regras de validação, consulte as classes de `routes`, `controllers`, `services`, `models` e `dao` em `src/api/`.
