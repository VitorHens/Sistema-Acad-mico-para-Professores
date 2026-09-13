# 🎓 Sistema Acadêmico para Professores

<p align="center">
  <img src="public/imagens/logo.png" alt="Logo do Sistema Acadêmico" width="180">
</p>

Sistema web desenvolvido para gerenciamento de informações acadêmicas, com uma interface voltada ao trabalho de professores. A aplicação integra **front-end, API REST, autenticação JWT e banco de dados MySQL**.

## 🚀 Funcionalidades

- 👨‍🎓 Gerenciamento de alunos
- 👨‍🏫 Gerenciamento de professores
- 📚 Gerenciamento de disciplinas
- 🏫 Gerenciamento de turmas
- 📝 Controle de matrículas
- 📊 Cadastro de notas e frequência
- 🔐 Login de professores
- 🔑 Proteção de rotas com JWT (JSON Web Token)
- 🔄 Operações CRUD: cadastro, consulta, atualização e exclusão
- 💾 Persistência de dados em MySQL utilizando PDO

## 🛠️ Tecnologias utilizadas

- PHP
- Slim Framework 4
- JavaScript
- HTML5
- Bootstrap
- MySQL
- PDO
- JWT (`firebase/php-jwt`)
- Composer
- API REST

## 🏗️ Arquitetura

O back-end é organizado em camadas para separar as responsabilidades da aplicação:

```text
Interface Web
     ↓
   API REST
     ↓
Controllers
     ↓
 Services
     ↓
   DAOs
     ↓
  MySQL
```

O projeto também utiliza **Models, Routes e Middlewares** para organizar entidades, endpoints e autenticação.

## 🔐 Autenticação

O acesso aos recursos protegidos é feito por meio de **JWT (JSON Web Token)**. Após o login, o token é utilizado nas requisições para validar o acesso do professor aos recursos da API.

## 📡 API REST

A documentação dos principais endpoints está disponível em [`API.md`](./API.md).

A aplicação utiliza localmente:

```text
http://localhost:8080
```

## ▶️ Como executar

### 1. Clone o repositório

```bash
git clone https://github.com/VitorHens/Sistema-Acad-mico-para-Professores.git
cd Sistema-Acad-mico-para-Professores
```

### 2. Instale as dependências

```bash
composer install
```

### 3. Configure o MySQL

Configure o banco de dados utilizado pelo projeto conforme a conexão definida pela aplicação e garanta que as tabelas necessárias estejam criadas antes de iniciar a API.

### 4. Inicie o servidor

```bash
composer start
```

Também é possível iniciar diretamente com:

```bash
php -S localhost:8080 -t public
```

Depois acesse a aplicação pelo navegador em `http://localhost:8080`.

## 📁 Estrutura principal

```text
├── public/             # Interface web e ponto de entrada da aplicação
├── src/api/            # Código da API
├── docs/               # Documentação complementar
├── API.md              # Documentação dos endpoints
├── composer.json       # Dependências e scripts PHP
└── composer.lock       # Versões das dependências
```

## 🎯 Objetivo do projeto

O projeto foi desenvolvido para praticar a construção de uma aplicação web completa, incluindo **consumo de API, autenticação, operações CRUD, organização em camadas e integração com banco de dados relacional**.

---

Desenvolvido por **Vitor Hens**.
