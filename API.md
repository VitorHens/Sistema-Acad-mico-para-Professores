# 📡 Documentação da API

Documentação completa dos endpoints disponíveis na API de Gestão de RH.

---

## 🌐 URL Base

```
http://localhost:8080
```

---

## 👤 Alunos

### 1. Listar Todos os Alunos

**Endpoint:** `GET /alunos`

**Resposta (200 OK):**
```json
{
  {
	"success": true,
	"message": "Busca realizada com sucesso",
	"data": {
		"alunos": [
			{
				"alu_id": 1,
				"alu_nome": "Ana"
			},
			{
				"alu_id": 2,
				"alu_nome": "Bruno"
			},
			{
				"alu_id": 3,
				"alu_nome": "Carlos"
      }
    ]
  }
 }
}
```

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/alunos
```

### 2. Buscar Aluno por ID

**Endpoint:** `GET /alunos/{alu_id}`

**Parâmetros de URL:**
- `alu_id` (int obrigatório)

**Resposta (200 OK):**
```json
{
	"success": true,
	"message": "Executado com sucesso",
	"data": {
		"alunos": {
			"alu_id": 1,
			"alu_nome": "Ana"
		}
	}
}
```

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/alunos/1
```

### 3. Criar Aluno

**Endpoint:** `POST /alunos`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"aluno":{
		"alu_nome": "teste"
	}
}
```

**Resposta (201 Created):**
```json
{
	"success": true,
	"message": "Cadastro realizado com sucesso",
	"data": {
		"Alunos": [
			{
				"alu_id": 7,
				"alu_nome": "teste"
			}
		]
	}
}
```

**Exemplo cURL:**
```bash
curl -X POST http://localhost:8080/alunos \
  -H "Content-Type: application/json" \
  -d '{"aluno":{"alu_nome":"teste"}}'
```

### 4. Atualizar Aluno

**Endpoint:** `PUT /alunos/{alu_id}`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"aluno":{
		"alu_nome": "Luiz"
	}
}
```

**Exemplo cURL:**
```bash
curl -X PUT http://localhost:8080/alunos/7 \
  -H "Content-Type: application/json" \
  -d '{"aluno":{"alu_nome":"Luiz"}}'
```

### 5. Deletar Aluno

**Endpoint:** `DELETE /alunos/{alu_id}`

**Exemplo cURL:**
```bash
curl -X DELETE http://localhost:8080/alunos/7
```

### 6. Contar Alunos

**Endpoint:** `GET /alunos/count`

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/alunos/count
```

---

##  👤 Professor

### 1. Listar Todos os Professores

**Endpoint:** `GET /professores`

**Resposta (200 OK):**
```json
{
	"success": true,
	"message": "Busca realizada com sucesso",
	"data": {
		"Professores": [
			{
				"prof_id": 1,
				"prof_nome": "Gabriel"
			},
			{
				"prof_id": 2,
				"prof_nome": "Heitor"
			},
			{
				"prof_id": 3,
				"prof_nome": "Iara"
			}
		]
	}
}
```

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/professores
```

### 2. Buscar Professor por ID

**Endpoint:** `GET /professores/{prof_id}`

**Parâmetros de URL:**
- `prof_id` (int obrigatório)

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/professores/1
```

### 3. Criar Professor

**Endpoint:** `POST /professores`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"professor":{
		"prof_nome": "teste"
	}
}
```

**Exemplo cURL:**
```bash
curl -X POST http://localhost:8080/professores \
  -H "Content-Type: application/json" \
  -d '{
	"professor":{
		"prof_nome": "teste"
	}
}'
```

### 4. Atualizar Professor

**Endpoint:** `PUT /professores/{prof_id}`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"professor":{
		"prof_nome": "Juliano"
	}
}
```

**Exemplo cURL:**
```bash
curl -X PUT http://localhost:8080/funcionarios/1 \
  -H "Content-Type: application/json" \
  -d '{
	"professor":{
		"prof_nome": "Juliano"
	}
}'
```

### 5. Deletar Professor

**Endpoint:** `DELETE /professores/{prof_id}`

**Exemplo cURL:**
```bash
curl -X DELETE http://localhost:8080/professores/4
```

### 6. Contar Professor

**Endpoint:** `GET /professores/count`

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/professores/count
```

---
## 📦 Disciplina

### 1. Listar Disciplina

**Endpoint:** `GET /disciplinas`

**Resposta (200 OK):**
```json
{
	"success": true,
	"message": "Busca realizada com sucesso",
	"data": {
		"disciplinas": [
			{
				"dis_id": 1,
				"dis_nome": "MySQL"
			},
			{
				"dis_id": 2,
				"dis_nome": "HTML"
			},
			{
				"dis_id": 3,
				"dis_nome": "CSS"
			},
			{
				"dis_id": 4,
				"dis_nome": "NoSQL"
			},
			{
				"dis_id": 5,
				"dis_nome": "PHP"
			}
		]
	}
}
```

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/disciplinas
```

### 2. Buscar Disciplina por ID

**Endpoint:** `GET /disciplinas/{dis_id}`

**Parâmetros de URL:**
- `dis_id` (int obrigatório)

**Resposta (200 OK):**
```json
{
	"success": true,
	"message": "Executado com sucesso",
	"data": {
		"disciplinas": {
			"dis_id": 2,
			"dis_nome": "HTML"
		}
	}
}
```

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/disciplinas/2
```

### 3. Criar Disciplina

**Endpoint:** `POST /disciplinas`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"disciplina":{
		"dis_nome": "teste"
	}
} 
```

**Resposta (201 Created):**
```json
{
	"success": true,
	"message": "Cadastro realizado com sucesso",
	"data": {
		"disciplinas": [
			{
				"dis_id": 6,
				"dis_nome": "teste"
			}
		]
	}
}
```

**Exemplo cURL:**
```bash
curl -X POST http://localhost:8080/disciplinas \
  -H "Content-Type: application/json" \
  -d '{"disciplina":{"dis_nome":"Português"}}'
```

### 4. Atualizar Disciplina

**Endpoint:** `PUT /disciplinas/{dis_id}`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"disciplina":{
		"dis_nome": "Portugues"
	}
}
```

**Exemplo cURL:**
```bash
curl -X PUT http://localhost:8080/disciplinas/6 \
  -H "Content-Type: application/json" \
  -d '{"aluno":{"alu_nome":"Luiz"}}'
```

### 5. Deletar Disciplina

**Endpoint:** `DELETE /disciplinas/{dis_id}`

**Exemplo cURL:**
```bash
curl -X DELETE http://localhost:8080/disciplinas/6
```

### 6. Contar Disciplina

**Endpoint:** `GET /disciplinas/count`

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/disciplinas/count
```

---

## 👤👤 Turma

### 1. Listar Todas as Turmas

**Endpoint:** `GET /turmas`

**Resposta (200 OK):**
```json
{
	"success": true,
	"message": "Executado com sucesso",
	"data": {
		"Turmas": [
			{
				"tur_id": 1,
				"Disciplina": {
					"dis_id": 1,
					"dis_nome": "MySQL"
				},
				"Professor": {
					"prof_id": 1,
					"prof_nome": "Gabriel"
				}
			},
			{
				"tur_id": 2,
				"Disciplina": {
					"dis_id": 2,
					"dis_nome": "HTML"
				},
				"Professor": {
					"prof_id": 2,
					"prof_nome": "Heitor"
				}
			},
			{
				"tur_id": 3,
				"Disciplina": {
					"dis_id": 3,
					"dis_nome": "CSS"
				},
				"Professor": {
					"prof_id": 3,
					"prof_nome": "Iara"
				}
			},
			{
				"tur_id": 4,
				"Disciplina": {
					"dis_id": 5,
					"dis_nome": "PHP"
				},
				"Professor": {
					"prof_id": 2,
					"prof_nome": "Heitor"
				}
			},
		]
	}
}
```

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/turmas
```

### 2. Buscar Turmas por ID

**Endpoint:** `GET /turmas/{tur_id}`

**Parâmetros de URL:**
- `tur_id` (int obrigatório)

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/turmas/1
```

### 3. Criar Turmas

**Endpoint:** `POST /turmas`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"turma":{
		"tur_id": 6,
		"disciplina":{
			"dis_id": 5
		},
			"professor":{
				"prof_id": 3
			}
	}
}
```

**Exemplo cURL:**
```bash
curl -X POST http://localhost:8080/turmas \
  -H "Content-Type: application/json" \
  -d '{
	"turma":{
		"tur_id": 6,
		"disciplina":{
			"dis_id": 5
		},
			"professor":{
				"prof_id": 3
			}
	}
}'
```

### 4. Atualizar Turmas

**Endpoint:** `PUT /turmas/{tur_id}`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"turma":{
		"tur_id": 6,
		"disciplina":{
			"dis_id": 5
		},
			"professor":{
				"prof_id": 3
			}
	}
}
```

**Exemplo cURL:**
```bash
curl -X PUT http://localhost:8080/turmas/1 \
  -H "Content-Type: application/json" \
  -d '{
	"professor":{
		"prof_nome": "Juliano"
	}
}'
```

### 5. Deletar Turmas

**Endpoint:** `DELETE /turmas/{tur_id}`

**Exemplo cURL:**
```bash
curl -X DELETE http://localhost:8080/turmas/4
```

### 6. Contar Turmas

**Endpoint:** `GET /turmas/count`

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/turmas/count
```

---
## 📠Matricula

### 1. Listar Todos as Matriculas

**Endpoint:** `GET /matriculas`

**Resposta (200 OK):**
```json
{
	"success": true,
	"message": "Executado com sucesso",
	"data": {
		"Matriculas": [
			{
				"matr_id": 1,
				"Turma": {
					"tur_id": 1,
					"Disciplina": {
						"dis_id": 1,
						"dis_nome": "MySQL"
					},
					"Professor": {
						"prof_id": 1,
						"prof_nome": "Gabriel"
					}
				},
				"Aluno": {
					"alu_id": 1,
					"alu_nome": "Ana"
				}
			},
			{
				"matr_id": 2,
				"Turma": {
					"tur_id": 1,
					"Disciplina": {
						"dis_id": 1,
						"dis_nome": "MySQL"
					},
					"Professor": {
						"prof_id": 1,
						"prof_nome": "Gabriel"
					}
				},
				"Aluno": {
					"alu_id": 2,
					"alu_nome": "Bruno"
				}
			},
			{
				"matr_id": 3,
				"Turma": {
					"tur_id": 2,
					"Disciplina": {
						"dis_id": 2,
						"dis_nome": "HTML"
					},
					"Professor": {
						"prof_id": 2,
						"prof_nome": "Heitor"
					}
				},
				"Aluno": {
					"alu_id": 1,
					"alu_nome": "Ana"
				}
			},
			{
				"matr_id": 4,
				"Turma": {
					"tur_id": 2,
					"Disciplina": {
						"dis_id": 2,
						"dis_nome": "HTML"
					},
					"Professor": {
						"prof_id": 2,
						"prof_nome": "Heitor"
					}
				},
				"Aluno": {
					"alu_id": 3,
					"alu_nome": "Carlos"
				}
			}
		]
	}
}
```

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/matriculas
```

### 2. Buscar Matricula por ID

**Endpoint:** `GET /matriculas/{matr_id}`

**Parâmetros de URL:**
- `matr_id` (int obrigatório)

**Resposta (200 OK):**
```json
{
	"success": true,
	"message": "Executado com sucesso",
	"data": {
		"alunos": {
			"alu_id": 1,
			"alu_nome": "Ana"
		}
	}
}
```

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/matriculas/1
```

### 3. Criar Aluno

**Endpoint:** `POST /matriculas`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"matricula":{
		 "turma":{
			"tur_id": 3
		 },
      "aluno":{
				"alu_id": 6
			}
 	}
}
```

**Resposta (201 Created):**
```json
{
	"success": true,
	"message": "Cadastro realizado com sucesso",
	"data": {
		"Matriculas": [
			{
				"matr_id": 10,
				"Turma": {
					"tur_id": 3,
					"Disciplina": {
						"dis_id": 3,
						"dis_nome": "CSS"
					},
					"Professor": {
						"prof_id": 3,
						"prof_nome": "Iara"
					}
				},
				"Aluno": {
					"alu_id": 5,
					"alu_nome": "Eduarda"
				}
			}
		]
	}
}
```

**Exemplo cURL:**
```bash
curl -X POST http://localhost:8080/matriculas \
  -H "Content-Type: application/json" \
  -d '{"cargo":{"nomeCargo":"Desenvolvedor Sênior"}}'
```

### 4. Atualizar Aluno

**Endpoint:** `PUT /matriculas/{matr_id}`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"matricula":{
		"matr_id": 3,
		 "turma":{
			"tur_id": 3
		 },
			"aluno":{
				"alu_id": 5
			}
 	}
}
```

**Exemplo cURL:**
```bash
curl -X PUT http://localhost:8080/matriculas/7 \
  -H "Content-Type: application/json" \
  -d '{"aluno":{"alu_nome":"Luiz"}}'
```

### 5. Deletar Aluno

**Endpoint:** `DELETE /matriculas/{alu_id}`

**Exemplo cURL:**
```bash
curl -X DELETE http://localhost:8080/matriculas/7
```

### 6. Contar Matriculas

**Endpoint:** `GET /matriculas/count`

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/matriculas/count
```

---

## 🔟 Nota

### 1. Listar Todas as Notas

**Endpoint:** `GET /notas`

**Resposta (200 OK):**
```json
{
	"success": true,
	"message": "Executado com sucesso",
	"data": {
		"Notas": [
			{
				"fre_id": 1,
				"fre": 90,
				"nota": 80,
				"matr_id": 1,
				"Matricula": {
					"matr_id": 1,
					"Turma": {
						"tur_id": 1,
						"Disciplina": {
							"dis_id": 1,
							"dis_nome": "MySQL"
						},
						"Professor": {
							"prof_id": 1,
							"prof_nome": "Gabriel"
						}
					},
					"Aluno": {
						"alu_id": 1,
						"alu_nome": "Ana"
					}
				}
			},
			{
				"fre_id": 2,
				"fre": 80,
				"nota": 50,
				"matr_id": 2,
				"Matricula": {
					"matr_id": 2,
					"Turma": {
						"tur_id": 1,
						"Disciplina": {
							"dis_id": 1,
							"dis_nome": "MySQL"
						},
						"Professor": {
							"prof_id": 1,
							"prof_nome": "Gabriel"
						}
					},
					"Aluno": {
						"alu_id": 2,
						"alu_nome": "Bruno"
					}
				}
			},
			{
				"fre_id": 3,
				"fre": 60,
				"nota": 70,
				"matr_id": 3,
				"Matricula": {
					"matr_id": 3,
					"Turma": {
						"tur_id": 2,
						"Disciplina": {
							"dis_id": 2,
							"dis_nome": "HTML"
						},
						"Professor": {
							"prof_id": 2,
							"prof_nome": "Heitor"
						}
					}
				}
			}
		]
	}
}
```

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/notas
```

### 2. Buscar Nota por ID

**Endpoint:** `GET /notas/{fre_id}`

**Parâmetros de URL:**
- `prof_id` (int obrigatório)

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/notas/1
```

### 3. Criar Notas

**Endpoint:** `POST /notas`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"nota":{
		"fre" : 35,
		"nota" : 45,
		"matr_id": 3
		}
}
```

**Exemplo cURL:**
```bash
curl -X POST http://localhost:8080/notas \
  -H "Content-Type: application/json" \
  -d '{
	"nota":{
		"fre" : 35,
		"nota" : 45,
		"matr_id": 3
		}
}'
```

### 4. Atualizar Nota

**Endpoint:** `PUT /notas/{fre_id}`

**Cabeçalhos:**
- `Content-Type: application/json`

**Corpo da Requisição:**
```json
{
	"nota":{
		"fre" : 35,
		"nota" : 45,
		"matr_id": 3
		}
}
```

**Exemplo cURL:**
```bash
curl -X PUT http://localhost:8080/notas/1 \
  -H "Content-Type: application/json" \
  -d '{
	"nota":{
		"fre" : 35,
		"nota" : 45,
		"matr_id": 3
		}
}'
```

### 5. Deletar Professor

**Endpoint:** `DELETE /notas/{fre_id}`

**Exemplo cURL:**
```bash
curl -X DELETE http://localhost:8080/notas/4
```

### 6. Contar Professor

**Endpoint:** `GET /notas/count`

**Exemplo cURL:**
```bash
curl -X GET http://localhost:8080/notas/count
```

---
## 🔄 Códigos HTTP

| Código | Significado                  |
|--------|------------------------------|
| 200    | Requisição executada com sucesso |
| 201    | Registro criado com sucesso  |
| 204    | Exclusão realizada com sucesso |
| 400    | Erro de validação            |
| 404    | Registro não encontrado      |
| 500    | Erro interno do servidor     |

---

## 📊 Estrutura de Resposta

### Sucesso
```json
{
  "success": true,
  "message": "Descrição do resultado",
  "data": {}
}
```

### Erro
```json
{
  "success": false,
  "message": "Descrição do erro",
  "error": {}
}
```

---

## 🧪 Testando no Postman

1. **Criar Collection**
2. **Definir variável:**
   - `base_url = http://localhost:8080`
3. **Importar endpoints**
4. **Testar operações CRUD**

---

**Última atualização:** 2 de Maio de 2026