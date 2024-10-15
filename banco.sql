CREATE TABLE userz(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	nome VARCHAR(255),
	usuario VARCHAR(255),
	senha_usuario VARCHAR(255),
	codigo_autenticacao INT NULL,
	data_codigo_autenticacao DATETIME NULL
);