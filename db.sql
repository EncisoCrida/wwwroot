-- Active: 1712278207443@@localhost@3306
DROP DATABASE IF EXISTS db_bots;
CREATE DATABASE db_bots;
USE db_bots;

DROP TABLE IF EXISTS tb_users;
CREATE TABLE tb_users(
    id_user         INT AUTO_INCREMENT,
    nombre          VARCHAR(100) NOT NULL,
    apellido        VARCHAR(100) NOT NULL,
    estado          INT NOT NULL,
    usuario         VARCHAR(15) NOT NULL UNIQUE,
    clave           VARCHAR(500) NOT NULL,
    rol             VARCHAR(20) NOT NULL,
    telefono        VARCHAR(20) NOT NULL,
    correo          VARCHAR(100) NOT NULL,
    fecha_registro  DATETIME NOT NULL,
    PRIMARY KEY(id_user)
);

INSERT INTO `tb_users`(`nombre`,`apellido`,`estado`,`usuario`,`clave`,`rol`,`telefono`,`correo`,`fecha_registro`) VALUES('Cristian David','Enciso Gomez',1,'crida','$2a$12$6CtzG./EwaN8B5zLnQwI7ee5xy3HEZVxlvnAIz21xnUJzEKoA4R/S','usuario','3196558337','cridaengo1209@gmail.com','2024-05-30 11:34:16');

DROP TABLE IF EXISTS login_token;
CREATE TABLE `login_token` (
  `id` int(11) AUTO_INCREMENT,
  `usuario` varchar(200) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `selector_hash` varchar(255) NOT NULL,
  `is_expired` int(11) NOT NULL DEFAULT '0',
  `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(`id`)
);


DROP TABLE IF EXISTS `tb_bots`;
CREATE TABLE `tb_bots` (
  `id_bot` INT AUTO_INCREMENT,
  `igg_id` INT NOT NULL,
  `id_user` INT NOT NULL,
  `fecha_expire` DATETIME NOT NULL,
  `fecha_registro` DATETIME NOT NULL,
  PRIMARY KEY(`id_bot`)
);

ALTER TABLE `tb_bots`
ADD CONSTRAINT `user_bot`
FOREIGN KEY (`id_user`)
REFERENCES `tb_users`(`id_user`)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

INSERT INTO `tb_bots` (`id_bot`, `igg_id`, `id_user`, `fecha_expire`, `fecha_registro`) VALUES (NULL, '1875366782', '1', '2024-05-30 23:20:38.000000', '2024-05-30 23:20:38.000000'), (NULL, '1800026971', '1', '2024-05-30 23:20:38.000000', '2024-05-30 23:20:38.000000');



DELIMITER ;
DROP FUNCTION IF EXISTS buscarUsuario;
DELIMITER $$
    CREATE FUNCTION `buscarUsuario` (UsuarioBuscar VARCHAR(100))
    RETURNS INTEGER
    BEGIN
    declare resultado INT(2);
    set resultado:=(select count(*) from tb_users where usuario = UsuarioBuscar);
    if resultado > 0 then
        RETURN 1;
        else
        return 2;
    END IF;
END$$

DELIMITER ;
DROP FUNCTION IF EXISTS buscarClave;
DELIMITER $$
    CREATE FUNCTION `buscarClave` (UsuarioBuscar varchar(100))
    RETURNS VARCHAR(500)
    BEGIN
    DECLARE cla VARCHAR(500);
    set cla = '';
    SET cla = (SELECT clave FROM tb_users WHERE usuario=UsuarioBuscar);
    RETURN cla;
END$$