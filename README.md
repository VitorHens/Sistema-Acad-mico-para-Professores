### 🎓 Sistema Acadêmico para Professores

Sistema web desenvolvido para gerenciamento de informações acadêmicas, permitindo que professores administrem alunos, disciplinas, turmas, matrículas, notas e frequência através de uma interface visual.

O sistema possui autenticação de professores com **JWT (JSON Web Token)**, utilizando nome e ID para realizar o login. Após a autenticação, o token é armazenado no navegador e utilizado nas requisições da API.

A aplicação conta com operações completas de **CRUD**, permitindo cadastrar, listar, atualizar e excluir registros diretamente pela interface web.

O back-end foi desenvolvido em **PHP utilizando Slim Framework**, organizado em camadas com Controllers, Services, DAOs, Models, Routes e Middlewares. Os dados são armazenados em um banco **MySQL**, com conexão realizada através de PDO.

Principais funcionalidades:
- 👨‍🎓 Gerenciamento de alunos
- 👨‍🏫 Gerenciamento de professores
- 📚 Gerenciamento de disciplinas
- 🏫 Gerenciamento de turmas
- 📝 Controle de matrículas
- 📊 Cadastro de notas e frequência
- 🔐 Login de professores
- 🔑 Autenticação utilizando JWT
- 🔄 Operações CRUD através de uma API REST
- 💾 Integração com banco de dados MySQL

**Tecnologias:** `PHP` • `JavaScript` • `HTML5` • `Bootstrap` • `Slim Framework` • `MySQL` • `PDO` • `REST API` • `JWT` • `Composer`
