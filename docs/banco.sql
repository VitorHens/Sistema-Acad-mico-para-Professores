create database universidadeWeb;


use universidadeWeb;

create table aluno(

	alu_id int primary key auto_increment,
    alu_nome varchar(255) not null,
    alu_status varchar(255) not null
    
);

create table professor(

	prof_id int primary key auto_increment,
    prof_nome varchar(255) not null
    
);


create table disciplina(

	dis_id int primary key auto_increment,
    dis_nome varchar(255) not null

);

create table turma(

	tur_id int primary key auto_increment,
	dis_id int,
    prof_id int,
    
    foreign key (dis_id) references disciplina(dis_id),
    foreign key (prof_id) references professor(prof_id)

);

create table matricula(

	matr_id int primary key auto_increment,
	tur_id int not null,
    alu_id int not null,
    
	foreign key (tur_id) references turma(tur_id),
    foreign key (alu_id) references aluno(alu_id)

);	

create table notas_frequencia(

	fre_id int primary key auto_increment,
    fre float,
    nota int,
    matr_id int,
    
    foreign key (matr_id) references matricula(matr_id)

);



insert into aluno (alu_nome, alu_status) values

	('Ana','ativo'),
    ('Bruno','ativo'),
    ('Carlos','inativo'),
    ('Daniela','ativo'),
    ('Eduarda','ativo'),
    ('Felipe','inativo')  

;

insert into professor (prof_nome) values

	('Gabriel'),
    ('Heitor'),
    ('Iara')

;

insert into disciplina (dis_nome) values

	('MySQL'),
    ('HTML'),
    ('CSS'),
    ('NoSQL'),
    ('PHP')

;

insert into turma (dis_id, prof_id) values

	(1,1),
    (2,2),
    (3,3),
    (5,2),
    (4,1)

;

insert into matricula (tur_id, alu_id) values

	(1,1),
    (1,2),
    (2,1),
    (2,3),
    (3,4),
    (3,5),
    (4,1),
    (5,6)

    
;

insert into notas_frequencia (nota, fre, matr_id) values

	(80,90,1),
    (50,80,2),
    (70,60,3),
    (90,95,4),
    (85,80,5),
    (40,50,6),
    (100,100,7),
    (60,75,8)

;s