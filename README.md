# 🎓 Sistema Acadêmico para Professores

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

- **PHP**
- **Slim Framework 4**
- **JavaScript**
- **HTML5**
- **Bootstrap**
- **MySQL**
- **PDO**
- **JWT** (`firebase/php-jwt`)
- **Composer**
- **API REST**

## 🏗️ Organização do back-end

O projeto utiliza uma arquitetura em camadas para separar as responsabilidades da aplicação:

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

Também são utilizados **Models, Routes e Middlewares** para organizar as entidades, endpoints e autenticação da aplicação.

## 🔐 Autenticação

O acesso aos recursos protegidos é feito por meio de **JWT (JSON Web Token)**. Após o login, o token é utilizado nas requisições para validar o acesso do usuário aos recursos da API.

## 📡 API REST

A aplicação disponibiliza endpoints para as operações de gerenciamento do sistema. A documentação detalhada das rotas e exemplos de requisições está disponível em [`API.md`](./API.md).

URL utilizada no ambiente local:

```text
http://localhost:8080
```

## 📦 Instalação das dependências

Com o PHP e o Composer instalados, execute na pasta do projeto:

```bash
composer install
```

## 📁 Estrutura principal

```text
├── public/             # Interface web
├── src/api/            # Código da API
├── docs/               # Documentação complementar
├── API.md              # Documentação dos endpoints
├── composer.json       # Dependências e configuração PHP
└── composer.lock       # Versões das dependências
```

## 🎯 Objetivo do projeto

O projeto foi desenvolvido com o objetivo de praticar a construção de uma aplicação web completa, incluindo **consumo de API, autenticação, operações CRUD, organização em camadas e integração com banco de dados relacional**.

---

Desenvolvido por **Vitor Hens**.
