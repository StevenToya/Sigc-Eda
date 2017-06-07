-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 06-10-2016 a las 09:31:10
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
-- Estructura de tabla para la tabla `aud_area`
--

CREATE TABLE IF NOT EXISTS `aud_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aud_base`
--

CREATE TABLE IF NOT EXISTS `aud_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_creador` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `material` int(1) NOT NULL DEFAULT '0',
  `descripcion` longtext NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_creador` (`id_creador`),
  KEY `key_base_area` (`id_area`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aud_categoria`
--

CREATE TABLE IF NOT EXISTS `aud_categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `orden` int(2) NOT NULL,
  `estado` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aud_item`
--

CREATE TABLE IF NOT EXISTS `aud_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_base` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `tipo` int(1) NOT NULL COMMENT '1=si_no; 2=texto; 3=archivo',
  `pregunta` varchar(150) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_base` (`id_base`),
  KEY `id_categoria` (`id_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aud_solicitud`
--

CREATE TABLE IF NOT EXISTS `aud_solicitud` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_base` int(11) NOT NULL,
  `id_creador` int(11) NOT NULL,
  `id_solicitud` int(11) DEFAULT NULL,
  `id_tramite` int(11) DEFAULT NULL,
  `id_une_pedido` int(11) DEFAULT NULL,
  `id_sra_tramite` int(11) DEFAULT NULL,
  `id_realizado` int(11) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_registro` datetime NOT NULL,
  `fecha_finalizado` datetime DEFAULT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  `observacion` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_base` (`id_base`),
  KEY `id_creador` (`id_creador`),
  KEY `id_solicitud` (`id_solicitud`),
  KEY `id_tramite` (`id_tramite`),
  KEY `id_realizado` (`id_realizado`),
  KEY `id_sra_tramite` (`id_sra_tramite`),
  KEY `id_une_pedido` (`id_une_pedido`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aud_solicitud_item`
--

CREATE TABLE IF NOT EXISTS `aud_solicitud_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `si_no` varchar(2) DEFAULT NULL,
  `respuesta` longtext,
  `archivo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitud` (`id_solicitud`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=90 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aud_solicitud_material`
--

CREATE TABLE IF NOT EXISTS `aud_solicitud_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(11) NOT NULL,
  `respuesta` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capacitacion`
--

CREATE TABLE IF NOT EXISTS `capacitacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicial` time NOT NULL,
  `hora_final` time NOT NULL,
  `total_hora` varchar(5) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `lugar` varchar(200) NOT NULL,
  `documento` varchar(100) NOT NULL,
  `observacion` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`,`id_programa`),
  KEY `id_programa` (`id_programa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capacitacion_persona`
--

CREATE TABLE IF NOT EXISTS `capacitacion_persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_capacitacion` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_capacitacion` (`id_capacitacion`,`id_persona`),
  KEY `id_persona` (`id_persona`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE IF NOT EXISTS `cargo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `descripcion` longtext NOT NULL,
  `estado` int(1) NOT NULL,
  `id_instancia` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_instancia` (`id_instancia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `mensaje` mediumtext NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componente`
--

CREATE TABLE IF NOT EXISTS `componente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_alarma_equipo_material`
--

CREATE TABLE IF NOT EXISTS `conf_alarma_equipo_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_equipo_material` int(11) NOT NULL,
  `id_tecnologia` int(11) NOT NULL,
  `empresa` varchar(50) NOT NULL,
  `cantidad_min` int(10) NOT NULL,
  `cantidad_max` int(10) DEFAULT NULL,
  `tipo` int(1) NOT NULL COMMENT '1=bajo; 2=medio; 3=alto',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE IF NOT EXISTS `departamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE IF NOT EXISTS `documento` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tipo` int(1) NOT NULL COMMENT '1=conductor ; 2=vehiculo',
  `descripcion` longtext NOT NULL,
  `carga` varchar(1) NOT NULL DEFAULT 's',
  `fecha_vencimiento` varchar(1) NOT NULL,
  `fecha_revision` varchar(1) NOT NULL,
  `revision_mes` int(10) NOT NULL,
  `archivo` varchar(200) NOT NULL,
  `estado` int(1) NOT NULL COMMENT '1=habilitado; 2=desabilitado',
  `id_instancia` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_ambiental`
--

CREATE TABLE IF NOT EXISTS `documento_ambiental` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fecha_expedicion` date DEFAULT NULL,
  `archivo` varchar(200) NOT NULL,
  `estado` int(1) NOT NULL COMMENT '1=nuevo ; 2=viejo',
  `fecha_registro` datetime NOT NULL,
  `observacion` longtext NOT NULL,
  `fase` int(1) NOT NULL COMMENT '1=sin_revisar ; 2=revisado ; 3=rechazado',
  `id_documento` int(20) NOT NULL,
  `id_usuario` int(20) NOT NULL,
  `id_empresa` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_empresa`
--

CREATE TABLE IF NOT EXISTS `documento_empresa` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fecha_expedicion` date DEFAULT NULL,
  `archivo` varchar(200) NOT NULL,
  `estado` int(1) NOT NULL COMMENT '1=nuevo ; 2=viejo',
  `fecha_registro` datetime NOT NULL,
  `observacion` longtext NOT NULL,
  `fase` int(1) NOT NULL COMMENT '1=sin_revisar ; 2=revisado ; 3=rechazado',
  `id_documento` int(20) NOT NULL,
  `id_usuario` int(20) NOT NULL,
  `id_empresa` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_vehiculo`
--

CREATE TABLE IF NOT EXISTS `documento_vehiculo` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_expedicion` date DEFAULT NULL,
  `archivo` varchar(200) NOT NULL,
  `estado` int(1) NOT NULL COMMENT '1=nuevo ; 2=viejo',
  `fecha_registro` datetime NOT NULL,
  `fase` int(1) NOT NULL COMMENT '1=sin_revisar ; 2=revisado ; 3=rechazado',
  `id_documento` int(20) NOT NULL,
  `id_usuario` int(20) NOT NULL,
  `id_vehiculo` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE IF NOT EXISTS `empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `contrato` varchar(200) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `representante_nombre` varchar(200) NOT NULL,
  `representante_cargo` varchar(200) NOT NULL,
  `coordinador_1` varchar(200) NOT NULL,
  `coordinador_1_licencia` varchar(200) NOT NULL,
  `coordinador_2` varchar(200) NOT NULL,
  `coordinador_2_licencia` varchar(200) NOT NULL,
  `coordinador_3` varchar(200) NOT NULL,
  `coordinador_3_licencia` varchar(200) NOT NULL,
  `coordinador_1_amb` varchar(200) NOT NULL,
  `coordinador_2_amb` varchar(200) NOT NULL,
  `coordinador_3_amb` varchar(200) NOT NULL,
  `coordinador_1_amb_licencia` varchar(200) NOT NULL,
  `coordinador_2_amb_licencia` varchar(200) NOT NULL,
  `coordinador_3_amb_licencia` varchar(200) NOT NULL,
  `coordinador_1_amb_archivo` varchar(200) NOT NULL,
  `coordinador_2_amb_archivo` varchar(200) NOT NULL,
  `coordinador_3_amb_archivo` varchar(200) NOT NULL,
  `trabajadores_cantidad` varchar(10) NOT NULL,
  `programa_salud` varchar(1) NOT NULL,
  `sistema_gestion` varchar(1) NOT NULL,
  `coordinador_1_archivo` varchar(200) NOT NULL,
  `coordinador_2_archivo` varchar(200) NOT NULL,
  `coordinador_3_archivo` varchar(200) NOT NULL,
  `programa_salud_amb` varchar(1) NOT NULL,
  `sistema_gestion_amb` varchar(1) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_registro_ambiental` datetime DEFAULT NULL,
  `estado` int(1) NOT NULL,
  `estado_ambiental` int(1) NOT NULL,
  `id_instancia` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_instancia` (`id_instancia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_material`
--

CREATE TABLE IF NOT EXISTS `equipo_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_1` int(50) NOT NULL,
  `codigo_2` varchar(50) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `tipo` int(1) NOT NULL COMMENT '1=equipo, 2=material',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=699 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_serial`
--

CREATE TABLE IF NOT EXISTS `equipo_serial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_equipo_material` int(11) NOT NULL,
  `id_tramite` int(11) DEFAULT NULL,
  `id_localidad` int(11) NOT NULL,
  `id_localidad_carga` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `serial` varchar(200) NOT NULL,
  `estado` int(1) NOT NULL COMMENT '1=libre, 2=en uso, 3=malo, 4=perdido',
  `zona` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_equipo_material` (`id_equipo_material`,`id_tramite`,`id_localidad`,`id_usuario`),
  KEY `id_tramite` (`id_tramite`),
  KEY `id_localidad` (`id_localidad`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_localidad_carga` (`id_localidad_carga`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14026 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frente_trabajo`
--

CREATE TABLE IF NOT EXISTS `frente_trabajo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_municipio` int(11) NOT NULL,
  `nombre_1` varchar(100) NOT NULL,
  `nombre_2` varchar(100) NOT NULL,
  `tecnologia` varchar(100) NOT NULL,
  `archivo_1` varchar(100) NOT NULL,
  `archivo_2` varchar(100) NOT NULL,
  `archivo_3` varchar(100) NOT NULL,
  `archivo_4` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `id_instancia` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_municipio` (`id_municipio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `front_localidad`
--

CREATE TABLE IF NOT EXISTS `front_localidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `estado` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=229 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `front_tecnico`
--

CREATE TABLE IF NOT EXISTS `front_tecnico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_torre` int(11) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `cedula` int(15) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `instalacion` varchar(1) NOT NULL DEFAULT 'n',
  `reparacion` varchar(1) NOT NULL DEFAULT 'n',
  `retiro` varchar(1) NOT NULL DEFAULT 'n',
  `fecha` date NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `front_tecnico_localidad`
--

CREATE TABLE IF NOT EXISTS `front_tecnico_localidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tecnico` int(11) NOT NULL,
  `id_localidad` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `front_torre`
--

CREATE TABLE IF NOT EXISTS `front_torre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `cedula` int(50) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hv_documento_persona`
--

CREATE TABLE IF NOT EXISTS `hv_documento_persona` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_expedicion` date DEFAULT NULL,
  `archivo` varchar(200) NOT NULL,
  `estado` int(1) NOT NULL COMMENT '1=nuevo ; 2=viejo',
  `fecha_registro` datetime NOT NULL,
  `fase` int(1) NOT NULL COMMENT '1=sin_revisar ; 2=revisado ; 3=rechazado',
  `id_documento` int(20) NOT NULL,
  `id_usuario` int(20) NOT NULL,
  `id_persona` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hv_estudio`
--

CREATE TABLE IF NOT EXISTS `hv_estudio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `nivel` varchar(100) NOT NULL,
  `instituto` varchar(150) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `fecha_inicial` date NOT NULL,
  `fecha_final` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hv_laboral`
--

CREATE TABLE IF NOT EXISTS `hv_laboral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `empresa` varchar(100) NOT NULL,
  `cargo` varchar(100) NOT NULL,
  `jefe` varchar(100) NOT NULL,
  `jefe_telefono` varchar(100) NOT NULL,
  `fecha_inicial` date NOT NULL,
  `fecha_final` date NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hv_persona`
--

CREATE TABLE IF NOT EXISTS `hv_persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_instancia` int(11) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `identificacion` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `celular` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `estado` int(2) NOT NULL DEFAULT '1',
  `fecha_registro` datetime NOT NULL,
  `id_usuario_creador` int(11) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `motivo_rechazo` longtext,
  PRIMARY KEY (`id`),
  KEY `id_usuario_creador` (`id_usuario_creador`),
  KEY `id_instancia` (`id_instancia`),
  KEY `id_municipio` (`id_municipio`),
  KEY `id_cargo` (`id_cargo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hv_referencia`
--

CREATE TABLE IF NOT EXISTS `hv_referencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `tipo` int(1) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `celular` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incremento`
--

CREATE TABLE IF NOT EXISTS `incremento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ano` int(4) NOT NULL,
  `valor` decimal(3,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidacion_caja_extension`
--

CREATE TABLE IF NOT EXISTS `liquidacion_caja_extension` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_zona` int(11) NOT NULL,
  `id_tipo_trabajo` int(11) NOT NULL,
  `caja_adicional` int(20) NOT NULL,
  `extension` int(20) NOT NULL,
  `valor` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_zona` (`id_zona`),
  KEY `id_tipo_trabajo` (`id_tipo_trabajo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidacion_equipo_material`
--

CREATE TABLE IF NOT EXISTS `liquidacion_equipo_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_trabajo` int(11) NOT NULL,
  `id_equipo_material` int(11) NOT NULL,
  `ano` int(4) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `valor` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_trabajo` (`id_tipo_trabajo`),
  KEY `id_equipo_material` (`id_equipo_material`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidacion_zona`
--

CREATE TABLE IF NOT EXISTS `liquidacion_zona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_trabajo` int(11) NOT NULL,
  `id_tecnologia` int(11) NOT NULL,
  `id_zona` int(11) NOT NULL,
  `ano` int(4) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `item` varchar(50) NOT NULL,
  `servicio` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_trabajo` (`id_tipo_trabajo`),
  KEY `id_zona` (`id_zona`),
  KEY `id_tecnologia` (`id_tecnologia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=937 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidar_tramite`
--

CREATE TABLE IF NOT EXISTS `liquidar_tramite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tramite` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo` int(1) NOT NULL COMMENT '1=automatico; 2=manual',
  `valor` varchar(100) NOT NULL,
  `observacion` longtext NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tramite` (`id_tramite`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidad`
--

CREATE TABLE IF NOT EXISTS `localidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=230 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material_bodega`
--

CREATE TABLE IF NOT EXISTS `material_bodega` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_localidad` int(11) NOT NULL,
  `codigo_localidad` int(11) DEFAULT NULL,
  `zona` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_localidad` (`id_localidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=243 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material_traza`
--

CREATE TABLE IF NOT EXISTS `material_traza` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_equipo_material` int(11) NOT NULL,
  `id_localidad` int(11) NOT NULL,
  `id_localidad_carga` int(11) DEFAULT NULL,
  `id_tramite` int(11) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `cantidad` int(20) NOT NULL,
  `tipo` int(1) NOT NULL COMMENT '1=instalado, 2=cargado',
  `fecha` datetime NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `cantidad_auditor` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_equipo_material` (`id_equipo_material`,`id_localidad`,`id_tramite`),
  KEY `id_localidad` (`id_localidad`),
  KEY `id_tramite` (`id_tramite`),
  KEY `id_localidad_carga` (`id_localidad_carga`),
  KEY `id_pedido` (`id_pedido`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5994 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo_seguridad`
--

CREATE TABLE IF NOT EXISTS `modulo_seguridad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `componente` varchar(20) NOT NULL,
  `modulo` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE IF NOT EXISTS `municipio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1103 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ord_tramite`
--

CREATE TABLE IF NOT EXISTS `ord_tramite` (
  `id` int(11) NOT NULL,
  `tipo_trabajo` varchar(200) NOT NULL,
  `pedido` varchar(100) NOT NULL,
  `zona` varchar(100) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `tecnologia` varchar(150) NOT NULL,
  `clase_servicio` varchar(150) NOT NULL,
  `tipo_cliente` varchar(100) NOT NULL,
  `cliente` varchar(150) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `barrio` varchar(100) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  `orden` int(100) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ord_tramite_cita`
--

CREATE TABLE IF NOT EXISTS `ord_tramite_cita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tramite` int(11) NOT NULL,
  `fecha_cita` date NOT NULL,
  `franja_cita` varchar(5) NOT NULL,
  `estado` int(1) NOT NULL COMMENT '1=asignado; 2=cumplido;  3=incumplido medio ;  4=incumplido total',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_equipo_historial`
--

CREATE TABLE IF NOT EXISTS `pedido_equipo_historial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_serial` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_serial` (`id_serial`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_equipo_material`
--

CREATE TABLE IF NOT EXISTS `pedido_equipo_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_localidad` int(11) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `estado` int(1) NOT NULL COMMENT '1=abierto, 2=cerrado',
  `tipo` int(1) NOT NULL COMMENT '1=equipo, 2=materiales',
  `fecha` date NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_localidad` (`id_localidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE IF NOT EXISTS `permiso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_componente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1412 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa`
--

CREATE TABLE IF NOT EXISTS `programa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `descripcion` longtext NOT NULL,
  `hora` int(5) NOT NULL,
  `id_instancia` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_instancia` (`id_instancia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa_cargo`
--

CREATE TABLE IF NOT EXISTS `programa_cargo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_programa` int(11) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_programa` (`id_programa`,`id_cargo`),
  KEY `id_cargo` (`id_cargo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_fecha`
--

CREATE TABLE IF NOT EXISTS `registro_fecha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tramite_instalacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_alarma_equipo_material`
--

CREATE TABLE IF NOT EXISTS `reporte_alarma_equipo_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tramite` int(11) NOT NULL,
  `id_conf_equipo_material` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `estado` int(11) NOT NULL COMMENT '1=iniciado; 2=visto; 3=resuelto',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=535 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `serial_traza`
--

CREATE TABLE IF NOT EXISTS `serial_traza` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_equipo_serial` int(11) NOT NULL,
  `id_localidad` int(11) NOT NULL,
  `id_tramite` int(11) DEFAULT NULL,
  `estado` int(1) NOT NULL COMMENT '1=libre, 2=en uso, 3=malo, 4=perdido',
  `actual` varchar(1) NOT NULL DEFAULT 's' COMMENT 's=es actual, n=viejo',
  `zona` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `fecha` datetime NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `observacion` longtext,
  PRIMARY KEY (`id`),
  KEY `id_equipo_serial` (`id_equipo_serial`,`id_localidad`,`id_tramite`),
  KEY `id_localidad` (`id_localidad`),
  KEY `id_tramite` (`id_tramite`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1563 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_pago`
--

CREATE TABLE IF NOT EXISTS `soporte_pago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ano_mes` varchar(8) NOT NULL,
  `archivo_1` varchar(100) DEFAULT NULL,
  `archivo_2` varchar(100) DEFAULT NULL,
  `archivo_3` varchar(100) DEFAULT NULL,
  `archivo_4` varchar(100) DEFAULT NULL,
  `archivo_5` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sra_distribuidor`
--

CREATE TABLE IF NOT EXISTS `sra_distribuidor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `distribuidor` varchar(100) NOT NULL,
  `orden` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=99 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sra_tramite`
--

CREATE TABLE IF NOT EXISTS `sra_tramite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_trabajo` varchar(200) NOT NULL,
  `pedido` varchar(100) NOT NULL,
  `zona` varchar(100) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `distribuidor` varchar(100) DEFAULT NULL,
  `armario` varchar(100) DEFAULT NULL,
  `caja` varchar(100) DEFAULT NULL,
  `tecnologia` varchar(150) NOT NULL,
  `clase_servicio` varchar(150) NOT NULL,
  `tipo_cliente` varchar(100) NOT NULL,
  `cliente` varchar(150) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `barrio` varchar(100) NOT NULL,
  `fecha_cita` date NOT NULL,
  `franja_horaria` varchar(5) NOT NULL,
  `agendador` varchar(150) NOT NULL,
  `observacion` longtext NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  `orden` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4732 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_car`
--

CREATE TABLE IF NOT EXISTS `stohome_car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_coordinador`
--

CREATE TABLE IF NOT EXISTS `stohome_coordinador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_ejecutado`
--

CREATE TABLE IF NOT EXISTS `stohome_ejecutado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `valor` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `observacion` longtext,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`),
  KEY `id_item` (`id_item`),
  KEY `id_periodo` (`id_periodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21828 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_ejecutado_cmv`
--

CREATE TABLE IF NOT EXISTS `stohome_ejecutado_cmv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `valor` varchar(50) NOT NULL,
  `tipo` int(1) NOT NULL COMMENT '1=alimentacion dia; 2=alimentacion mas alojamiento pernoctado',
  `fecha` date NOT NULL,
  `observacion` longtext,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`),
  KEY `id_periodo` (`id_periodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=337 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_item`
--

CREATE TABLE IF NOT EXISTS `stohome_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` int(1) NOT NULL COMMENT '1=rol; 2=por_dia; 3=por_unidad_trabajo',
  `nombre` varchar(100) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_no_ejecutado`
--

CREATE TABLE IF NOT EXISTS `stohome_no_ejecutado` (
  `id` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `observacion` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_periodo`
--

CREATE TABLE IF NOT EXISTS `stohome_periodo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicial` date NOT NULL,
  `fecha_final` date NOT NULL,
  `estado` int(11) NOT NULL COMMENT '1=abierto, 2=cerrado',
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_periodo_usuario`
--

CREATE TABLE IF NOT EXISTS `stohome_periodo_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_periodo` (`id_periodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_periodo_usuario_cmv`
--

CREATE TABLE IF NOT EXISTS `stohome_periodo_usuario_cmv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_periodo` (`id_periodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_persona`
--

CREATE TABLE IF NOT EXISTS `stohome_persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_plataforma` int(11) NOT NULL,
  `id_sap` int(11) NOT NULL,
  `id_car` int(11) DEFAULT NULL,
  `id_rubro` int(11) NOT NULL,
  `id_municipio` int(11) DEFAULT NULL,
  `identificacion` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `zona` varchar(100) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_coordinador` (`id_usuario`),
  KEY `id_plataforma` (`id_plataforma`),
  KEY `key_stohome_persona_sap` (`id_sap`),
  KEY `id_car` (`id_car`),
  KEY `id_rubro` (`id_rubro`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=595 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_persona_item`
--

CREATE TABLE IF NOT EXISTS `stohome_persona_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=782 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_plataforma`
--

CREATE TABLE IF NOT EXISTS `stohome_plataforma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=170 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_rubro`
--

CREATE TABLE IF NOT EXISTS `stohome_rubro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stohome_sap`
--

CREATE TABLE IF NOT EXISTS `stohome_sap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta` varchar(100) NOT NULL,
  `nombre` text NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_car`
--

CREATE TABLE IF NOT EXISTS `sto_car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_coordinador`
--

CREATE TABLE IF NOT EXISTS `sto_coordinador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_ejecutado`
--

CREATE TABLE IF NOT EXISTS `sto_ejecutado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `valor` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `observacion` longtext,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`),
  KEY `id_item` (`id_item`),
  KEY `id_periodo` (`id_periodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1086 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_ejecutado_cmv`
--

CREATE TABLE IF NOT EXISTS `sto_ejecutado_cmv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `valor` varchar(50) NOT NULL,
  `tipo` int(1) NOT NULL COMMENT '1=alimentacion dia; 2=alimentacion mas alojamiento pernoctado',
  `fecha` date NOT NULL,
  `observacion` longtext,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`),
  KEY `id_periodo` (`id_periodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_item`
--

CREATE TABLE IF NOT EXISTS `sto_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` int(1) NOT NULL COMMENT '1=rol; 2=por_dia; 3=por_unidad_trabajo',
  `nombre` varchar(100) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_no_ejecutado`
--

CREATE TABLE IF NOT EXISTS `sto_no_ejecutado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `observacion` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_periodo`
--

CREATE TABLE IF NOT EXISTS `sto_periodo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicial` date NOT NULL,
  `fecha_final` date NOT NULL,
  `estado` int(11) NOT NULL COMMENT '1=abierto, 2=cerrado',
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_periodo_usuario`
--

CREATE TABLE IF NOT EXISTS `sto_periodo_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_periodo` (`id_periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_periodo_usuario_cmv`
--

CREATE TABLE IF NOT EXISTS `sto_periodo_usuario_cmv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_periodo` (`id_periodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_persona`
--

CREATE TABLE IF NOT EXISTS `sto_persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_plataforma` int(11) NOT NULL,
  `id_sap` int(11) NOT NULL,
  `id_car` int(11) DEFAULT NULL,
  `id_rubro` int(11) NOT NULL,
  `id_municipio` int(11) DEFAULT NULL,
  `identificacion` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `zona` varchar(100) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_coordinador` (`id_usuario`),
  KEY `id_plataforma` (`id_plataforma`),
  KEY `key_sto_persona_sap` (`id_sap`),
  KEY `id_car` (`id_car`),
  KEY `id_rubro` (`id_rubro`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_persona_item`
--

CREATE TABLE IF NOT EXISTS `sto_persona_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_plataforma`
--

CREATE TABLE IF NOT EXISTS `sto_plataforma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_rubro`
--

CREATE TABLE IF NOT EXISTS `sto_rubro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sto_sap`
--

CREATE TABLE IF NOT EXISTS `sto_sap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta` varchar(100) NOT NULL,
  `nombre` text NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnico`
--

CREATE TABLE IF NOT EXISTS `tecnico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `cedula` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3015 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnologia`
--

CREATE TABLE IF NOT EXISTS `tecnologia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_trabajo`
--

CREATE TABLE IF NOT EXISTS `tipo_trabajo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(50) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `tipo` int(2) NOT NULL COMMENT '1=instalacion, 2=reconexion, 3=reparacion, 4=suspencion, 5=Retiro, 6=Prematricula',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramite`
--

CREATE TABLE IF NOT EXISTS `tramite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_trabajo` int(11) NOT NULL,
  `id_localidad` int(11) NOT NULL,
  `id_tecnico` int(11) DEFAULT NULL,
  `id_tecnologia` int(11) DEFAULT NULL,
  `id_usuario_liquida` int(11) DEFAULT NULL,
  `id_usuario_liquida_material` int(11) DEFAULT NULL,
  `id_usuario_liquida_equipo` int(11) DEFAULT NULL,
  `id_usuario_revisa` int(11) DEFAULT NULL,
  `id_usuario_revisa_material` int(11) DEFAULT NULL,
  `id_usuario_revisa_equipo` int(11) DEFAULT NULL,
  `ot` varchar(50) NOT NULL,
  `solicitud` varchar(50) NOT NULL,
  `fecha_atencion` datetime NOT NULL,
  `fecha_atencion_orden` datetime NOT NULL,
  `fecha_reportada` datetime NOT NULL,
  `fecha_asignacion` datetime NOT NULL,
  `fecha_creacion_oden` datetime NOT NULL,
  `garantia_vista` varchar(1) NOT NULL DEFAULT 'n',
  `unidad_operativa` varchar(200) NOT NULL,
  `codigo_unidad_operativa` int(50) NOT NULL,
  `codigo_tipo_paquete` varchar(50) NOT NULL,
  `tipo_paquete` varchar(200) NOT NULL,
  `tipo_producto` varchar(200) NOT NULL,
  `numero_servicio` varchar(200) NOT NULL,
  `tecnologia` varchar(200) DEFAULT NULL,
  `location_id` varchar(50) NOT NULL,
  `departamento` varchar(200) NOT NULL,
  `region` varchar(60) NOT NULL,
  `zona` varchar(50) NOT NULL,
  `nombre_cliente` varchar(200) NOT NULL,
  `identificacion_cliente` varchar(50) NOT NULL,
  `contrato` varchar(200) NOT NULL,
  `direccion_codigo` varchar(200) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `clase_servicio` varchar(200) NOT NULL,
  `plan_comercial` varchar(200) NOT NULL,
  `tipo_cliente` varchar(50) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `numero_orden` varchar(100) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `locaprod` varchar(100) NOT NULL,
  `estado_liquidacion` int(1) NOT NULL DEFAULT '1' COMMENT '1=sin liquidar; 2=liquidada',
  `valor_liquidado` varchar(15) NOT NULL,
  `ot_antecesor` int(11) DEFAULT NULL,
  `tipo_garantia` int(1) DEFAULT NULL,
  `fecha_ot_antecesor` datetime DEFAULT NULL,
  `equipo_cpe_prod` int(5) DEFAULT NULL,
  `equipo_stb_prod` int(5) DEFAULT NULL,
  `voz` int(10) DEFAULT NULL,
  `internet` int(10) DEFAULT NULL,
  `television` int(10) DEFAULT NULL,
  `fecha_creacion_producto` datetime DEFAULT NULL,
  `codigo_dano` varchar(20) DEFAULT NULL,
  `ultimo` varchar(1) NOT NULL DEFAULT 's',
  `contratista_valor` int(1) NOT NULL DEFAULT '1',
  `contratista_equipo` int(1) NOT NULL DEFAULT '1',
  `contratista_material` int(1) NOT NULL DEFAULT '1',
  `observacion_contratista` longtext,
  `observacion_contratista_material` longtext,
  `observacion_contratista_equipo` longtext,
  `observacion_edatel` longtext,
  `observacion_edatel_material` longtext,
  `observacion_edatel_equipo` longtext,
  `descripcion_dano` varchar(100) NOT NULL,
  `localizacion_dano` varchar(50) NOT NULL,
  `cantidad_cpe` int(5) DEFAULT NULL,
  `cantidad_stbox` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_trabajo` (`id_tipo_trabajo`,`id_localidad`,`id_tecnico`),
  KEY `id_localidad` (`id_localidad`,`id_tecnico`),
  KEY `id_tecnico` (`id_tecnico`),
  KEY `id_tecnologia` (`id_tecnologia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20379 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramite_comercial`
--

CREATE TABLE IF NOT EXISTS `tramite_comercial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tramite` int(11) NOT NULL,
  `codigo` int(10) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tramite` (`id_tramite`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4479 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramite_motivo`
--

CREATE TABLE IF NOT EXISTS `tramite_motivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tramite` int(11) NOT NULL,
  `codigo` int(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tramite` (`id_tramite`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4479 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramite_servicio`
--

CREATE TABLE IF NOT EXISTS `tramite_servicio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tramite` int(11) NOT NULL,
  `codigo` int(10) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tramite` (`id_tramite`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4479 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramite_tecnologia`
--

CREATE TABLE IF NOT EXISTS `tramite_tecnologia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tramite` int(11) NOT NULL,
  `codigo` int(10) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tramite` (`id_tramite`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4269 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `une_dth`
--

CREATE TABLE IF NOT EXISTS `une_dth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido` varchar(100) NOT NULL,
  `cliente_identificacion` varchar(100) NOT NULL,
  `cliente_nombre` varchar(100) NOT NULL,
  `cliente_direccion` varchar(100) NOT NULL,
  `zona` varchar(100) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `cable_rg6` varchar(100) NOT NULL,
  `correa_rg6` varchar(100) NOT NULL,
  `correa_plastica` varchar(100) NOT NULL,
  `grapa` varchar(100) NOT NULL,
  `deco_1` varchar(100) DEFAULT NULL,
  `deco_2` varchar(100) DEFAULT NULL,
  `smart_card_1` varchar(100) DEFAULT NULL,
  `smart_card_2` varchar(100) DEFAULT NULL,
  `archivo_1` varchar(100) NOT NULL,
  `archivo_2` varchar(100) NOT NULL,
  `archivo_3` varchar(100) NOT NULL,
  `archivo_4` varchar(100) NOT NULL,
  `archivo_5` varchar(100) NOT NULL,
  `tiempo_transporte` varchar(100) NOT NULL,
  `tiempo_antena` varchar(100) NOT NULL,
  `tiempo_cableado` varchar(100) NOT NULL,
  `tiempo_aprovisionamiento` varchar(100) NOT NULL,
  `id_usuario` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `conector_carga` int(10) NOT NULL,
  `plato` int(10) NOT NULL,
  `lnb` int(10) NOT NULL,
  `conector_security` int(10) NOT NULL,
  `coordenada_n` varchar(100) NOT NULL,
  `coordenada_w` varchar(100) NOT NULL,
  `altura` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `une_equipo`
--

CREATE TABLE IF NOT EXISTS `une_equipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `une_item`
--

CREATE TABLE IF NOT EXISTS `une_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(11) NOT NULL,
  `valor` varchar(10) NOT NULL,
  `tipo` int(1) NOT NULL DEFAULT '1',
  `categoria` varchar(150) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `une_liquidacion`
--

CREATE TABLE IF NOT EXISTS `une_liquidacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_item` int(11) DEFAULT NULL,
  `codigo` varchar(10) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `une_material`
--

CREATE TABLE IF NOT EXISTS `une_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `une_pedido`
--

CREATE TABLE IF NOT EXISTS `une_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(100) NOT NULL,
  `ciudad` varchar(150) NOT NULL,
  `tipo_trabajo` varchar(150) NOT NULL,
  `nombre_funcionario` varchar(100) NOT NULL,
  `codigo_funcionario` varchar(150) NOT NULL,
  `segmento` varchar(150) NOT NULL,
  `producto` varchar(150) NOT NULL,
  `producto_homologado` varchar(150) NOT NULL,
  `tecnologia` varchar(150) NOT NULL,
  `proceso` varchar(150) NOT NULL,
  `empresa` varchar(150) NOT NULL,
  `fecha` date NOT NULL,
  `estado_material` int(1) NOT NULL DEFAULT '1' COMMENT '1=sin revisar; 2=aceptado; 3=rechazado',
  `fecha_material_eia` datetime DEFAULT NULL,
  `usuario_material_eia` int(11) DEFAULT NULL,
  `estado_equipo` int(1) NOT NULL DEFAULT '1' COMMENT '1=sin revisar; 2=aceptado; 3=rechazado',
  `estado_liquidacion` int(1) DEFAULT NULL,
  `fecha_liquidacion_eia` datetime DEFAULT NULL,
  `usuario_liquidacion_eia` int(11) DEFAULT NULL,
  `observacion_material` longtext NOT NULL,
  `observacion_material_liquidacion` longtext,
  `observacion_edatel` longtext,
  `observacion_edatel_liquidacion` longtext,
  `tipo` int(1) NOT NULL DEFAULT '1' COMMENT '1=hfc;2=dth',
  `cliente_nombre` longtext NOT NULL,
  `cliente_direccion` longtext NOT NULL,
  `nombre_empresa` varchar(200) NOT NULL,
  `extexiones_tv` varchar(200) NOT NULL,
  `tipo_direccion` varchar(200) NOT NULL,
  `cliente_id` varchar(200) NOT NULL,
  `servicio_instalado` varchar(200) NOT NULL,
  `reparacion` varchar(200) NOT NULL,
  `servicio_insta` varchar(200) NOT NULL,
  `estado_pedido` varchar(200) NOT NULL,
  `municipio_dane` varchar(200) NOT NULL,
  `departamento_dane` varchar(200) NOT NULL,
  `descripcion_falla` varchar(200) DEFAULT NULL,
  `inconsistencia` varchar(200) DEFAULT NULL,
  `stridentificacion` varchar(100) DEFAULT NULL,
  `migtras` varchar(100) DEFAULT NULL,
  `tipoinstaespecifico` varchar(100) DEFAULT NULL,
  `tipoinstageneral` varchar(100) DEFAULT NULL,
  `portafolio` varchar(100) DEFAULT NULL,
  `telefono_contacto` varchar(200) NOT NULL,
  `celular_contacto` varchar(200) NOT NULL,
  `microzona` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2036 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `une_pedido_equipo`
--

CREATE TABLE IF NOT EXISTS `une_pedido_equipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `serial` varchar(150) NOT NULL,
  `mac` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_equipo` (`id_equipo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `une_pedido_material`
--

CREATE TABLE IF NOT EXISTS `une_pedido_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `alarma` int(1) NOT NULL DEFAULT '1' COMMENT '1=sin alarma; 2=alerta baja; 3=alerta alta ',
  `numero_componente` varchar(100) NOT NULL,
  `unidad_material` varchar(100) NOT NULL,
  `identificador` varchar(100) NOT NULL,
  `min_ct` varchar(100) NOT NULL,
  `max_ct` varchar(100) NOT NULL,
  `min_pr` varchar(100) NOT NULL,
  `max_pr` varchar(100) NOT NULL,
  `nuevo_min` varchar(100) NOT NULL,
  `nuevo_max` varchar(100) NOT NULL,
  `alerta_cable_ct` varchar(100) NOT NULL,
  `calc_puntos_instalado` varchar(100) NOT NULL,
  `alerta_puntos_instalados` varchar(100) NOT NULL,
  `calc_conectores_inst` varchar(100) NOT NULL,
  `alerta_conectores_inst` varchar(100) NOT NULL,
  `calc_filtros_inst` varchar(100) NOT NULL,
  `alerta_filtro` varchar(100) NOT NULL,
  `precio` varchar(100) NOT NULL,
  `precio_x_cant_rep` varchar(100) NOT NULL,
  `difer_precio_x_cant_rep` varchar(100) NOT NULL,
  `li` varchar(100) NOT NULL,
  `ls` varchar(100) NOT NULL,
  `precio_x_dif_sobre_lim` varchar(100) NOT NULL,
  `alerta_cable_limites` varchar(100) NOT NULL,
  `alertado` varchar(100) NOT NULL,
  `reiterativo` varchar(100) NOT NULL,
  `cantidad_auditor` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_material` (`id_material`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2116 ;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE IF NOT EXISTS `vehiculo` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_tipo_vehiculo` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `pasajeros` int(3) NOT NULL,
  `carga` varchar(1) NOT NULL,
  `modelo` int(4) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '1',
  `id_persona` int(20) DEFAULT NULL,
  `id_instancia` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_instancia` (`id_instancia`),
  KEY `id_tipo_vehiculo` (`id_tipo_vehiculo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo_tipo`
--

CREATE TABLE IF NOT EXISTS `vehiculo_tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `estado` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zona`
--

CREATE TABLE IF NOT EXISTS `zona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `aud_base`
--
ALTER TABLE `aud_base`
  ADD CONSTRAINT `key_base_area` FOREIGN KEY (`id_area`) REFERENCES `aud_area` (`id`),
  ADD CONSTRAINT `key_base_usuario` FOREIGN KEY (`id_creador`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `aud_item`
--
ALTER TABLE `aud_item`
  ADD CONSTRAINT `key_item_base` FOREIGN KEY (`id_base`) REFERENCES `aud_item` (`id`),
  ADD CONSTRAINT `key_item_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `aud_categoria` (`id`);

--
-- Filtros para la tabla `aud_solicitud`
--
ALTER TABLE `aud_solicitud`
  ADD CONSTRAINT `key_solicitud_base` FOREIGN KEY (`id_base`) REFERENCES `aud_base` (`id`),
  ADD CONSTRAINT `key_solicitud_sra_tramite` FOREIGN KEY (`id_sra_tramite`) REFERENCES `sra_tramite` (`id`),
  ADD CONSTRAINT `key_solicitud_tramite` FOREIGN KEY (`id_tramite`) REFERENCES `tramite` (`id`),
  ADD CONSTRAINT `key_solicitud_une_pedido` FOREIGN KEY (`id_une_pedido`) REFERENCES `une_pedido` (`id`),
  ADD CONSTRAINT `key_solicitud_usuario_creador` FOREIGN KEY (`id_creador`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `key_solicitud_usuario_realizado` FOREIGN KEY (`id_realizado`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `key_solicitud_usuario_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `aud_solicitud_item`
--
ALTER TABLE `aud_solicitud_item`
  ADD CONSTRAINT `key_solicitud_item` FOREIGN KEY (`id_item`) REFERENCES `aud_item` (`id`),
  ADD CONSTRAINT `key_solicitud_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `aud_solicitud` (`id`);

--
-- Filtros para la tabla `capacitacion`
--
ALTER TABLE `capacitacion`
  ADD CONSTRAINT `pk_capacitacion_programa` FOREIGN KEY (`id_programa`) REFERENCES `programa` (`id`),
  ADD CONSTRAINT `pk_capacitacion_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `capacitacion_persona`
--
ALTER TABLE `capacitacion_persona`
  ADD CONSTRAINT `pk_capacitacion_persona_capacitacion` FOREIGN KEY (`id_capacitacion`) REFERENCES `capacitacion` (`id`),
  ADD CONSTRAINT `pk_capacitacion_persona_persona` FOREIGN KEY (`id_persona`) REFERENCES `hv_persona` (`id`);

--
-- Filtros para la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD CONSTRAINT `pk_cargo_instancia` FOREIGN KEY (`id_instancia`) REFERENCES `instancia` (`id`);

--
-- Filtros para la tabla `equipo_serial`
--
ALTER TABLE `equipo_serial`
  ADD CONSTRAINT `key_equipo_serial_equipo_material` FOREIGN KEY (`id_equipo_material`) REFERENCES `equipo_material` (`id`),
  ADD CONSTRAINT `key_equipo_serial_localidad` FOREIGN KEY (`id_localidad`) REFERENCES `localidad` (`id`),
  ADD CONSTRAINT `key_equipo_serial_localidad2` FOREIGN KEY (`id_localidad_carga`) REFERENCES `localidad` (`id`),
  ADD CONSTRAINT `key_equipo_serial_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido_equipo_material` (`id`),
  ADD CONSTRAINT `key_equipo_serial_tramite` FOREIGN KEY (`id_tramite`) REFERENCES `tramite` (`id`),
  ADD CONSTRAINT `key_equipo_serial_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `hv_estudio`
--
ALTER TABLE `hv_estudio`
  ADD CONSTRAINT `id_persona_fk2` FOREIGN KEY (`id_persona`) REFERENCES `hv_persona` (`id`);

--
-- Filtros para la tabla `hv_laboral`
--
ALTER TABLE `hv_laboral`
  ADD CONSTRAINT `id_persona_fk3` FOREIGN KEY (`id_persona`) REFERENCES `hv_persona` (`id`);

--
-- Filtros para la tabla `hv_persona`
--
ALTER TABLE `hv_persona`
  ADD CONSTRAINT `id_instancia_fk` FOREIGN KEY (`id_instancia`) REFERENCES `instancia` (`id`),
  ADD CONSTRAINT `id_usuario_fk` FOREIGN KEY (`id_usuario_creador`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `pk_persona_cargo` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`);

--
-- Filtros para la tabla `hv_referencia`
--
ALTER TABLE `hv_referencia`
  ADD CONSTRAINT `id_persona_fk` FOREIGN KEY (`id_persona`) REFERENCES `hv_persona` (`id`);

--
-- Filtros para la tabla `liquidacion_caja_extension`
--
ALTER TABLE `liquidacion_caja_extension`
  ADD CONSTRAINT `key_liquidacion_caj_ext_tipo_trabajo` FOREIGN KEY (`id_tipo_trabajo`) REFERENCES `tipo_trabajo` (`id`),
  ADD CONSTRAINT `key_liquidacion_caj_ext_zona` FOREIGN KEY (`id_zona`) REFERENCES `zona` (`id`);

--
-- Filtros para la tabla `liquidacion_equipo_material`
--
ALTER TABLE `liquidacion_equipo_material`
  ADD CONSTRAINT `key_liquidacion_equipo_material_equipo_material` FOREIGN KEY (`id_equipo_material`) REFERENCES `equipo_material` (`id`),
  ADD CONSTRAINT `key_liquidacion_equipo_material_tipo_trabajo` FOREIGN KEY (`id_tipo_trabajo`) REFERENCES `tipo_trabajo` (`id`);

--
-- Filtros para la tabla `liquidacion_zona`
--
ALTER TABLE `liquidacion_zona`
  ADD CONSTRAINT `key_liquidacion_zona_tecnologia` FOREIGN KEY (`id_tecnologia`) REFERENCES `tecnologia` (`id`),
  ADD CONSTRAINT `key_liquidacion_zona_tipo_trabajo` FOREIGN KEY (`id_tipo_trabajo`) REFERENCES `tipo_trabajo` (`id`),
  ADD CONSTRAINT `key_liquidacion_zona_zona` FOREIGN KEY (`id_zona`) REFERENCES `zona` (`id`);

--
-- Filtros para la tabla `liquidar_tramite`
--
ALTER TABLE `liquidar_tramite`
  ADD CONSTRAINT `key_iquidar_tramite_tramite` FOREIGN KEY (`id_tramite`) REFERENCES `tramite` (`id`),
  ADD CONSTRAINT `key_liquidar_tramite_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `material_bodega`
--
ALTER TABLE `material_bodega`
  ADD CONSTRAINT `key_material_bodega_localidad` FOREIGN KEY (`id_localidad`) REFERENCES `localidad` (`id`);

--
-- Filtros para la tabla `material_traza`
--
ALTER TABLE `material_traza`
  ADD CONSTRAINT `key_material_traza_equipo_material` FOREIGN KEY (`id_equipo_material`) REFERENCES `equipo_material` (`id`),
  ADD CONSTRAINT `key_material_traza_localidad` FOREIGN KEY (`id_localidad`) REFERENCES `localidad` (`id`),
  ADD CONSTRAINT `key_material_traza_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido_equipo_material` (`id`),
  ADD CONSTRAINT `key_material_traza_tramite` FOREIGN KEY (`id_tramite`) REFERENCES `tramite` (`id`);

--
-- Filtros para la tabla `pedido_equipo_historial`
--
ALTER TABLE `pedido_equipo_historial`
  ADD CONSTRAINT `key_pedido_equipo_historial_equipo_serial` FOREIGN KEY (`id_serial`) REFERENCES `equipo_serial` (`id`),
  ADD CONSTRAINT `key_pedido_equipo_historial_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido_equipo_material` (`id`);

--
-- Filtros para la tabla `pedido_equipo_material`
--
ALTER TABLE `pedido_equipo_material`
  ADD CONSTRAINT `key_pedido_localidad` FOREIGN KEY (`id_localidad`) REFERENCES `localidad` (`id`),
  ADD CONSTRAINT `key_pedido_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `programa`
--
ALTER TABLE `programa`
  ADD CONSTRAINT `pk_programa_instancia` FOREIGN KEY (`id_instancia`) REFERENCES `instancia` (`id`);

--
-- Filtros para la tabla `programa_cargo`
--
ALTER TABLE `programa_cargo`
  ADD CONSTRAINT `pk_programa_cargo_cargo` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`),
  ADD CONSTRAINT `pk_programa_cargo_programa` FOREIGN KEY (`id_programa`) REFERENCES `programa` (`id`);

--
-- Filtros para la tabla `serial_traza`
--
ALTER TABLE `serial_traza`
  ADD CONSTRAINT `key_serial_traza_equipo_serial` FOREIGN KEY (`id_equipo_serial`) REFERENCES `equipo_serial` (`id`),
  ADD CONSTRAINT `key_serial_traza_localidad` FOREIGN KEY (`id_localidad`) REFERENCES `localidad` (`id`),
  ADD CONSTRAINT `key_serial_traza_tramite` FOREIGN KEY (`id_tramite`) REFERENCES `tramite` (`id`);

--
-- Filtros para la tabla `stohome_coordinador`
--
ALTER TABLE `stohome_coordinador`
  ADD CONSTRAINT `key_coordinador_usuario2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `stohome_ejecutado`
--
ALTER TABLE `stohome_ejecutado`
  ADD CONSTRAINT `key_ejecutado_item2` FOREIGN KEY (`id_item`) REFERENCES `stohome_item` (`id`),
  ADD CONSTRAINT `key_ejecutado_periodo2` FOREIGN KEY (`id_periodo`) REFERENCES `stohome_periodo` (`id`),
  ADD CONSTRAINT `key_ejecutado_persona2` FOREIGN KEY (`id_persona`) REFERENCES `stohome_persona` (`id`);

--
-- Filtros para la tabla `sto_coordinador`
--
ALTER TABLE `sto_coordinador`
  ADD CONSTRAINT `key_coordinador_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `sto_ejecutado`
--
ALTER TABLE `sto_ejecutado`
  ADD CONSTRAINT `key_ejecutado_item` FOREIGN KEY (`id_item`) REFERENCES `sto_item` (`id`),
  ADD CONSTRAINT `key_ejecutado_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `sto_periodo` (`id`),
  ADD CONSTRAINT `key_ejecutado_persona` FOREIGN KEY (`id_persona`) REFERENCES `sto_persona` (`id`);

--
-- Filtros para la tabla `sto_periodo_usuario`
--
ALTER TABLE `sto_periodo_usuario`
  ADD CONSTRAINT `key_sto_periodo_usuario_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `sto_periodo` (`id`),
  ADD CONSTRAINT `key_sto_periodo_usuario_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `sto_periodo_usuario_cmv`
--
ALTER TABLE `sto_periodo_usuario_cmv`
  ADD CONSTRAINT `key_sto_periodo_usuario_cmv_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `sto_periodo` (`id`),
  ADD CONSTRAINT `key_sto_periodo_usuario_cmv_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `sto_persona`
--
ALTER TABLE `sto_persona`
  ADD CONSTRAINT `key_persona_plataforma` FOREIGN KEY (`id_plataforma`) REFERENCES `sto_plataforma` (`id`),
  ADD CONSTRAINT `key_personal_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `key_sto_persona_car` FOREIGN KEY (`id_car`) REFERENCES `sto_car` (`id`),
  ADD CONSTRAINT `key_sto_persona_rubro` FOREIGN KEY (`id_rubro`) REFERENCES `sto_rubro` (`id`),
  ADD CONSTRAINT `key_sto_persona_sap` FOREIGN KEY (`id_sap`) REFERENCES `sto_sap` (`id`);

--
-- Filtros para la tabla `sto_persona_item`
--
ALTER TABLE `sto_persona_item`
  ADD CONSTRAINT `key_persona_item_item` FOREIGN KEY (`id_item`) REFERENCES `sto_item` (`id`),
  ADD CONSTRAINT `key_persona_item_persona` FOREIGN KEY (`id_persona`) REFERENCES `sto_persona` (`id`);

--
-- Filtros para la tabla `tramite`
--
ALTER TABLE `tramite`
  ADD CONSTRAINT `key_tramite_localidad` FOREIGN KEY (`id_localidad`) REFERENCES `localidad` (`id`),
  ADD CONSTRAINT `key_tramite_tecnico` FOREIGN KEY (`id_tecnico`) REFERENCES `tecnico` (`id`),
  ADD CONSTRAINT `key_tramite_tecnologia` FOREIGN KEY (`id_tecnologia`) REFERENCES `tecnologia` (`id`),
  ADD CONSTRAINT `key_tramite_tipo_trabajo` FOREIGN KEY (`id_tipo_trabajo`) REFERENCES `tipo_trabajo` (`id`);

--
-- Filtros para la tabla `tramite_comercial`
--
ALTER TABLE `tramite_comercial`
  ADD CONSTRAINT `key_comercial_tramite` FOREIGN KEY (`id_tramite`) REFERENCES `tramite` (`id`);

--
-- Filtros para la tabla `tramite_motivo`
--
ALTER TABLE `tramite_motivo`
  ADD CONSTRAINT `key_motivo_tramite` FOREIGN KEY (`id_tramite`) REFERENCES `tramite` (`id`);

--
-- Filtros para la tabla `tramite_servicio`
--
ALTER TABLE `tramite_servicio`
  ADD CONSTRAINT `key_servicio_tramite` FOREIGN KEY (`id_tramite`) REFERENCES `tramite` (`id`);

--
-- Filtros para la tabla `tramite_tecnologia`
--
ALTER TABLE `tramite_tecnologia`
  ADD CONSTRAINT `key_tecnologia_tramite` FOREIGN KEY (`id_tramite`) REFERENCES `tramite` (`id`);

--
-- Filtros para la tabla `une_liquidacion`
--
ALTER TABLE `une_liquidacion`
  ADD CONSTRAINT `key_liquidacion_item` FOREIGN KEY (`id_item`) REFERENCES `une_item` (`id`),
  ADD CONSTRAINT `key_liquidacion_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `une_pedido` (`id`);

--
-- Filtros para la tabla `une_pedido_equipo`
--
ALTER TABLE `une_pedido_equipo`
  ADD CONSTRAINT `key_pedido_equipo_equipo` FOREIGN KEY (`id_equipo`) REFERENCES `une_equipo` (`id`),
  ADD CONSTRAINT `key_pedido_equipo_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `une_pedido` (`id`);

--
-- Filtros para la tabla `une_pedido_material`
--
ALTER TABLE `une_pedido_material`
  ADD CONSTRAINT `key_pedido_material_material` FOREIGN KEY (`id_material`) REFERENCES `une_material` (`id`),
  ADD CONSTRAINT `key_pedido_material_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `une_pedido` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
