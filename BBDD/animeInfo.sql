-- MySQL Script generated by MySQL Workbench
-- Wed Dec  4 19:12:03 2024
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema animeinfo
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema animeinfo
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `animeinfo` DEFAULT CHARACTER SET utf8 ;
USE `animeinfo` ;

-- -----------------------------------------------------
-- Table `animeinfo`.`anime`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `animeinfo`.`anime` (
  `idAnime` MEDIUMINT NOT NULL,
  `nombreAnime` VARCHAR(100) NOT NULL,
  `tipo` ENUM('TV', 'MOVIE', 'OVA', 'ONA', 'Special', 'TV Short', 'Music') NOT NULL,
  `capitulos` SMALLINT NOT NULL,
  `estado` ENUM('RELEASING', 'FINISHED') NOT NULL,
  `sinopsis` VARCHAR(1000) NOT NULL,
  `portada` MEDIUMBLOB NOT NULL,
  `fechaInicio` VARCHAR(10) NOT NULL,
  `fechaFin` VARCHAR(10) NULL DEFAULT NULL,
  `cantidadMeGusta` MEDIUMINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`idAnime`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `animeinfo`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `animeinfo`.`usuario` (
  `idUsuario` CHAR(7) NOT NULL,
  `nombreUsuario` VARCHAR(20) NOT NULL,
  `correo` VARCHAR(30) NOT NULL,
  `passwd` VARCHAR(60) NOT NULL,
  `fotoPerfil` MEDIUMBLOB NULL DEFAULT NULL,
  `rol` ENUM('cliente', 'admin') NOT NULL,
  PRIMARY KEY (`idUsuario`),
  UNIQUE INDEX `nombreUsuario_UNIQUE` (`nombreUsuario` ASC) ,
  UNIQUE INDEX `correo_UNIQUE` (`correo` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `animeinfo`.`lista`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `animeinfo`.`lista` (
  `idLista` CHAR(7) NOT NULL,
  `nombreLista` VARCHAR(10) NOT NULL,
  `fechaCreacion` DATE NOT NULL,
  `Usuario_idUsuario` CHAR(7) NOT NULL,
  PRIMARY KEY (`idLista`, `Usuario_idUsuario`),
  INDEX `fk_Lista_Usuario1_idx` (`Usuario_idUsuario` ASC) ,
  CONSTRAINT `fk_Lista_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `animeinfo`.`usuario` (`idUsuario`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `animeinfo`.`almacena`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `animeinfo`.`almacena` (
  `Lista_idLista` CHAR(7) NOT NULL,
  `Anime_idAnime` MEDIUMINT NOT NULL,
  PRIMARY KEY (`Lista_idLista`, `Anime_idAnime`),
  INDEX `fk_Lista_has_Anime_Anime1_idx` (`Anime_idAnime` ASC) ,
  INDEX `fk_Lista_has_Anime_Lista1_idx` (`Lista_idLista` ASC) ,
  CONSTRAINT `fk_Lista_has_Anime_Anime1`
    FOREIGN KEY (`Anime_idAnime`)
    REFERENCES `animeinfo`.`anime` (`idAnime`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Lista_has_Anime_Lista1`
    FOREIGN KEY (`Lista_idLista`)
    REFERENCES `animeinfo`.`lista` (`idLista`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `animeinfo`.`genero`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `animeinfo`.`genero` (
  `idGenero` MEDIUMINT NOT NULL AUTO_INCREMENT,
  `genero` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`idGenero`))
ENGINE = InnoDB
AUTO_INCREMENT = 19
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `animeinfo`.`animegenero`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `animeinfo`.`animegenero` (
  `Anime_idAnime` MEDIUMINT NOT NULL,
  `Genero_idGenero` MEDIUMINT NOT NULL,
  PRIMARY KEY (`Anime_idAnime`, `Genero_idGenero`),
  INDEX `fk_AnimeGenero_Genero` (`Genero_idGenero` ASC) ,
  CONSTRAINT `fk_AnimeGenero_Anime`
    FOREIGN KEY (`Anime_idAnime`)
    REFERENCES `animeinfo`.`anime` (`idAnime`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_AnimeGenero_Genero`
    FOREIGN KEY (`Genero_idGenero`)
    REFERENCES `animeinfo`.`genero` (`idGenero`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `animeinfo`.`comenta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `animeinfo`.`comenta` (
  `idComentario` CHAR(7) NOT NULL,
  `Usuario_idUsuario` CHAR(7) NOT NULL,
  `Anime_idAnime` MEDIUMINT NOT NULL,
  `texto` VARCHAR(400) NOT NULL,
  `fechaPublicacion` DATETIME NOT NULL,
  PRIMARY KEY (`idComentario`),
  INDEX `fk_Usuario_has_Anime_Anime1_idx` (`Anime_idAnime` ASC) ,
  INDEX `fk_Usuario_has_Anime_Usuario_idx` (`Usuario_idUsuario` ASC) ,
  CONSTRAINT `fk_Usuario_has_Anime_Anime1`
    FOREIGN KEY (`Anime_idAnime`)
    REFERENCES `animeinfo`.`anime` (`idAnime`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Usuario_has_Anime_Usuario`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `animeinfo`.`usuario` (`idUsuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `animeinfo`.`darmegusta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `animeinfo`.`darmegusta` (
  `Usuario_idUsuario` CHAR(7) NOT NULL,
  `Anime_idAnime` MEDIUMINT NOT NULL,
  PRIMARY KEY (`Usuario_idUsuario`, `Anime_idAnime`),
  INDEX `fk_Usuario_has_Anime_Anime2_idx` (`Anime_idAnime` ASC) ,
  INDEX `fk_Usuario_has_Anime_Usuario1_idx` (`Usuario_idUsuario` ASC) ,
  CONSTRAINT `fk_Usuario_has_Anime_Anime2`
    FOREIGN KEY (`Anime_idAnime`)
    REFERENCES `animeinfo`.`anime` (`idAnime`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Usuario_has_Anime_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `animeinfo`.`usuario` (`idUsuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
