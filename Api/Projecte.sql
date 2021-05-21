-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Temps de generació: 21-05-2021 a les 17:25:33
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
-- Base de dades: `ProjecteUsuaris`
--
CREATE DATABASE IF NOT EXISTS `ProjecteUsuaris` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `ProjecteUsuaris`;

-- --------------------------------------------------------

--
-- Estructura de la taula `Assistencia`
--

CREATE TABLE `Assistencia` (
  `Alumne` int NOT NULL,
  `UF` int NOT NULL,
  `DataHora` datetime NOT NULL,
  `Present` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `GrupClasse`
--

CREATE TABLE `GrupClasse` (
  `UF` int NOT NULL,
  `Persona` int NOT NULL,
  `professor` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `Modul`
--

CREATE TABLE `Modul` (
  `codi` int NOT NULL,
  `Nom` varchar(50) DEFAULT NULL,
  `Abrev` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `Persona`
--

CREATE TABLE `Persona` (
  `codi` int NOT NULL,
  `Nom` varchar(60) DEFAULT NULL,
  `Cognoms` varchar(60) DEFAULT NULL,
  `professor` tinyint DEFAULT NULL,
  `Usuari` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `TokensUsuari`
--

CREATE TABLE `TokensUsuari` (
  `Usuari` int NOT NULL,
  `JWT` text CHARACTER SET ascii COLLATE ascii_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `UnitatFormativa`
--

CREATE TABLE `UnitatFormativa` (
  `codi` int NOT NULL,
  `Nom` varchar(50) DEFAULT NULL,
  `Abrev` varchar(3) DEFAULT NULL,
  `Hores` int NOT NULL,
  `Modul` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `Assistencia`
--
ALTER TABLE `Assistencia`
  ADD PRIMARY KEY (`Alumne`,`UF`,`DataHora`),
  ADD KEY `UF_idx` (`UF`);

--
-- Índexs per a la taula `GrupClasse`
--
ALTER TABLE `GrupClasse`
  ADD PRIMARY KEY (`UF`,`Persona`),
  ADD KEY `Alumne_idx` (`Persona`);

--
-- Índexs per a la taula `Modul`
--
ALTER TABLE `Modul`
  ADD PRIMARY KEY (`codi`),
  ADD UNIQUE KEY `Abrev` (`Abrev`);

--
-- Índexs per a la taula `Persona`
--
ALTER TABLE `Persona`
  ADD PRIMARY KEY (`codi`),
  ADD KEY `fk_usuari` (`Usuari`);

--
-- Índexs per a la taula `TokensUsuari`
--
ALTER TABLE `TokensUsuari`
  ADD PRIMARY KEY (`Usuari`);

--
-- Índexs per a la taula `UnitatFormativa`
--
ALTER TABLE `UnitatFormativa`
  ADD PRIMARY KEY (`codi`),
  ADD KEY `UFModul_idx` (`Modul`);

--
-- Índexs per a la taula `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `Modul`
--
ALTER TABLE `Modul`
  MODIFY `codi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la taula `Persona`
--
ALTER TABLE `Persona`
  MODIFY `codi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la taula `UnitatFormativa`
--
ALTER TABLE `UnitatFormativa`
  MODIFY `codi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la taula `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
-- Restriccions per a la taula `Persona`
--
ALTER TABLE `Persona`
  ADD CONSTRAINT `fk_usuari` FOREIGN KEY (`Usuari`) REFERENCES `users` (`id`);

--
-- Restriccions per a la taula `TokensUsuari`
--
ALTER TABLE `TokensUsuari`
  ADD CONSTRAINT `fk_tokensusuari` FOREIGN KEY (`Usuari`) REFERENCES `users` (`id`);

--
-- Restriccions per a la taula `UnitatFormativa`
--
ALTER TABLE `UnitatFormativa`
  ADD CONSTRAINT `UFModul` FOREIGN KEY (`Modul`) REFERENCES `Modul` (`codi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
