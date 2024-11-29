CREATE TABLE userz(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	nome VARCHAR(255) NOT NULL,
	usuario VARCHAR(255) NOT NULL,
	senha_usuario VARCHAR(255) NOT NULL,
	chave_recuperar_senha VARCHAR(255) NULL,
	codigo_autenticacao INT NULL,
	data_codigo_autenticacao DATETIME NULL,
	dt_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);