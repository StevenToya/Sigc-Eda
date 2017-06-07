-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 06-10-2016 a las 09:40:58
-- Versión del servidor: 5.5.52
-- Versión de PHP: 5.3.10-1ubuntu3.25

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `sigc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instancia`
--

CREATE TABLE IF NOT EXISTS `instancia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Volcado de datos para la tabla `instancia`
--

INSERT INTO `instancia` (`id`, `nombre`, `estado`) VALUES
(1, 'DEMO', 1),
(2, 'Secretria de Agricultura y Dasarrollo Rural\n', 1),
(3, 'Secretria Seccional de Salud y proteccion Social\n', 1),
(4, 'Secretaria de Educacion\n', 1),
(5, 'Secretria de la Mujer\r\n', 1),
(6, 'Secretria Gneral\r\n', 1),
(7, 'Secretaria de Gobierno', 1),
(8, 'Secrteria de Hacienda', 1),
(9, 'Secretaria de Infraestructura', 1),
(10, 'Secrteria de productividad y Competitividad', 1),
(11, 'Secretaria de Gestion Humana y desarrollo Organizacional', 1),
(12, 'Secretaria de Medio Ambiente', 1),
(13, 'Secretaria de Participacion Ciudadana y Desarrollo Social', 1),
(14, 'Secretaria de Minas', 1),
(15, 'Entidades Descentralizadas', 1),
(16, 'Dpto Administrativos', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
