-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 06-10-2016 a las 09:41:22
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
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) NOT NULL,
  `id_instancia` int(11) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `clave` longtext NOT NULL,
  `clave_dos` longtext,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `identificacion` varchar(45) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1' COMMENT '1=habilitado, 2=bloqueado',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`,`identificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usuario`, `id_instancia`, `id_municipio`, `clave`, `clave_dos`, `nombre`, `apellido`, `identificacion`, `direccion`, `correo`, `estado`) VALUES
(1, 'paul', 1, 0, 'e034fb6b66aacc1d48f445ddfb08da98', NULL, 'Erick Paul', 'Reyes Bejarano', '7225346', 'Calle 34 Numero 48 32', 'erickpaulr@gmail.com', 1),
(3, 'erickpaulr', 1, 137, 'c9148142f242b366966ddc6c57a7ff2e', NULL, 'Nombre', 'Apellido', '1111', 'direccion', 'erickpaulr@gmail.com', 1),
(4, 'erickpaulr1', 1, 526, 'af9fd068be46d2489b6381cf1519f53e', NULL, 'Asdewar Erwe', 'Fdsfsd', '1122', 'efr werewrwe', '\r\n				erickpaulr@gmail.com', 2),
(5, 'erickpaulr11', 1, 529, '079c4475402bd6e905c6e872277dfb42', NULL, 'Gonzalo', 'Mantilla Aponte', '115896541', 'Calle 41 N. 52 28', 'erickpaulr@gmail.com', 2),
(6, 'hzapata', 1, 70, '51fb13f2b308ce64f797effca1df8b94', NULL, 'Horacio Antonio', 'Zapata Cruz', '70850769', 'Calle 41 N. 52 28', 'hzapata@edatel.com.co', 2),
(7, 'referrer', 1, 70, 'caa5b823c50642f768015e320b18521f', NULL, 'Rafael Ernesto', 'Ferrer Barona', '11798135', 'Calle 41 N. 52 28', 'referrer@edatel.com.co', 2),
(8, 'rhernandez', 1, 70, '36cd7d21f118e54d5d7102b7a28f24cc', NULL, 'Rafael', 'Hernandez Meza', '1233211', 'Calle 41 N. 52 28', 'rhernandez@edatel.com.co', 2),
(9, 'manoriega', 1, 70, 'd446f531b34ab8b4feb72d6ad76a3d06', NULL, 'Manuel Antonio', 'Noriega Velasquez', '98652574', 'Calle 41 N. 52 28', 'manoriega@gmail.com', 1),
(10, 'dceballos1608', 1, 70, '134b531a90451ac8000c4208ec0af2dd', NULL, 'Diego Hernan', 'Ceballos Gutierrez', '71743908', 'Calle 41 52-28 MedellÃ­n Piso 13', 'dceballos1608@gmail.com', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
