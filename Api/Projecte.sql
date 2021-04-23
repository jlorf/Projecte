-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Temps de generació: 16-04-2021 a les 17:14:14
-- Versió del servidor: 8.0.21-0ubuntu0.20.04.4
-- Versió de PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `Projecte`
--
CREATE DATABASE IF NOT EXISTS `Projecte` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `Projecte`;

-- --------------------------------------------------------

--
-- Estructura de la taula `Assistencia`
--

DROP TABLE IF EXISTS `Assistencia`;
CREATE TABLE IF NOT EXISTS `Assistencia` (
  `Alumne` int NOT NULL,
  `UF` int NOT NULL,
  `DataHora` datetime NOT NULL,
  `Present` tinyint DEFAULT NULL,
  PRIMARY KEY (`Alumne`,`UF`,`DataHora`),
  KEY `UF_idx` (`UF`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `GrupClasse`
--

DROP TABLE IF EXISTS `GrupClasse`;
CREATE TABLE IF NOT EXISTS `GrupClasse` (
  `UF` int NOT NULL,
  `Persona` int NOT NULL,
  `professor` tinyint DEFAULT NULL,
  PRIMARY KEY (`UF`,`Persona`),
  KEY `Alumne_idx` (`Persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `Modul`
--

DROP TABLE IF EXISTS `Modul`;
CREATE TABLE IF NOT EXISTS `Modul` (
  `codi` int NOT NULL AUTO_INCREMENT,
  `Nom` varchar(50) DEFAULT NULL,
  `Abrev` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`codi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `Persona`
--

DROP TABLE IF EXISTS `Persona`;
CREATE TABLE IF NOT EXISTS `Persona` (
  `codi` int NOT NULL AUTO_INCREMENT,
  `Nom` varchar(60) DEFAULT NULL,
  `Cognoms` varchar(60) DEFAULT NULL,
  `professor` tinyint DEFAULT NULL,
  PRIMARY KEY (`codi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `UnitatFormativa`
--

DROP TABLE IF EXISTS `UnitatFormativa`;
CREATE TABLE IF NOT EXISTS `UnitatFormativa` (
  `codi` int NOT NULL AUTO_INCREMENT,
  `Nom` varchar(50) DEFAULT NULL,
  `Abrev` varchar(3) DEFAULT NULL,
  `Hores` int NOT NULL,
  `Modul` int DEFAULT NULL,
  PRIMARY KEY (`codi`),
  KEY `UFModul_idx` (`Modul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Restriccions per a les taules bolcades
--

--
-- Restriccions per a la taula `Assistencia`
--
ALTER TABLE `Assistencia`
  ADD CONSTRAINT `AlumneAssistencia` FOREIGN KEY (`Alumne`) REFERENCES `Persona` (`codi`),
  ADD CONSTRAINT `UFAssistencia` FOREIGN KEY (`UF`) REFERENCES `UnitatFormativa` (`codi`);

--
-- Restriccions per a la taula `GrupClasse`
--
ALTER TABLE `GrupClasse`
  ADD CONSTRAINT `Persona` FOREIGN KEY (`Persona`) REFERENCES `Persona` (`codi`),
  ADD CONSTRAINT `UF` FOREIGN KEY (`UF`) REFERENCES `UnitatFormativa` (`codi`);

--
-- Restriccions per a la taula `UnitatFormativa`
--
ALTER TABLE `UnitatFormativa`
  ADD CONSTRAINT `UFModul` FOREIGN KEY (`Modul`) REFERENCES `Modul` (`codi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
