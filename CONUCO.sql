-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 30-07-2018 a las 14:28:25
-- Versión del servidor: 5.5.60-0+deb8u1
-- Versión de PHP: 5.6.33-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `CONUCO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `censo`
--

CREATE TABLE IF NOT EXISTS `censo` (
`id_censo` int(11) NOT NULL,
  `anio` year(4) NOT NULL,
  `periodo` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `status` enum('Habilitado','Deshabilitado') NOT NULL,
  `editar` enum('Habilitado','Deshabilitado') NOT NULL,
  `encargado_registro` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `censo`
--

INSERT INTO `censo` (`id_censo`, `anio`, `periodo`, `descripcion`, `status`, `editar`, `encargado_registro`, `fecha_registro`) VALUES
(1, 2018, 1, 'prueba', 'Habilitado', 'Habilitado', 1, '2018-07-02 13:32:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `censo_comuna`
--

CREATE TABLE IF NOT EXISTS `censo_comuna` (
`id_censo_comuna` int(11) NOT NULL,
  `id_censo` int(11) NOT NULL,
  `id_comuna` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `censo_comuna_consejo`
--

CREATE TABLE IF NOT EXISTS `censo_comuna_consejo` (
`id_censo_comuna_consejo` int(11) NOT NULL,
  `id_censo_comuna` int(11) NOT NULL,
  `id_censo_consejo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `censo_comuna_coordinador`
--

CREATE TABLE IF NOT EXISTS `censo_comuna_coordinador` (
`id_censo_comuna_coordinador` int(11) NOT NULL,
  `id_permisologia` int(11) NOT NULL,
  `id_censo_comuna` int(11) NOT NULL,
  `encargado_registro` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `censo_consejo`
--

CREATE TABLE IF NOT EXISTS `censo_consejo` (
`id_censo_consejo` int(11) NOT NULL,
  `id_censo` int(11) NOT NULL,
  `id_consejo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `censo_consejo_coordinador`
--

CREATE TABLE IF NOT EXISTS `censo_consejo_coordinador` (
`id_censo_consejo_coordinador` int(11) NOT NULL,
  `id_permisologia` int(11) NOT NULL,
  `id_censo_consejo` int(11) NOT NULL,
  `encargado_registro` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `censo_productor`
--

CREATE TABLE IF NOT EXISTS `censo_productor` (
`id_productor` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_censo_consejo` int(11) NOT NULL,
  `id_rubro` int(11) NOT NULL,
  `hectareas` float(10,2) NOT NULL,
  `hectareas_plan` float(10,2) NOT NULL,
  `mecanizable` enum('SI','NO') NOT NULL,
  `semillas` enum('SI','NO') NOT NULL,
  `tipo_semilla` varchar(200) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `encargado_registro` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `censo_productor_siembra`
--

CREATE TABLE IF NOT EXISTS `censo_productor_siembra` (
`id_siembra` int(11) NOT NULL,
  `id_productor` int(11) NOT NULL,
  `id_rubro` int(11) NOT NULL,
  `hectareas` float(10,2) NOT NULL,
  `observacion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion`
--

CREATE TABLE IF NOT EXISTS `clasificacion` (
`id_clasificacion` int(11) NOT NULL,
  `clasificacion` varchar(100) NOT NULL,
  `status` enum('Habilitado','Deshabilitado') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clasificacion`
--

INSERT INTO `clasificacion` (`id_clasificacion`, `clasificacion`, `status`) VALUES
(1, 'Cereales', 'Habilitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comuna`
--

CREATE TABLE IF NOT EXISTS `comuna` (
`id_comuna` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `rif` varchar(20) NOT NULL,
  `comuna` varchar(250) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `id_parroquia` int(11) NOT NULL,
  `direccion` text NOT NULL,
  `status` enum('Habilitado','Deshabilitado') NOT NULL,
  `encargado_registro` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consejo`
--

CREATE TABLE IF NOT EXISTS `consejo` (
`id_consejo` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `rif` varchar(20) NOT NULL,
  `consejo` varchar(250) NOT NULL,
  `id_parroquia` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `comunidad` text NOT NULL,
  `direccion` text NOT NULL,
  `status` enum('Habilitado','Deshabilitado') NOT NULL,
  `encargado_rgistro` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consejo_tipo`
--

CREATE TABLE IF NOT EXISTS `consejo_tipo` (
`id_consejo_tipo` int(11) NOT NULL,
  `tipo` varchar(200) NOT NULL,
  `status` enum('Habilitado','Deshabilitado') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `consejo_tipo`
--

INSERT INTO `consejo_tipo` (`id_consejo_tipo`, `tipo`, `status`) VALUES
(2, 'Consejo Comunal', 'Habilitado'),
(3, 'Consejo Campesino', 'Habilitado'),
(4, 'Consejo de Pescadores', 'Habilitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE IF NOT EXISTS `estado` (
`id_estado` int(11) NOT NULL,
  `estado` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `iso` varchar(6) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `estado`, `iso`) VALUES
(1, 'Amazonas', 'VE-X'),
(2, 'Anzo&aacute;tegui', 'VE-B'),
(3, 'Apure', 'VE-C'),
(4, 'Aragua', 'VE-D'),
(5, 'Barinas', 'VE-E'),
(6, 'Bol&iacute;var', 'VE-F'),
(7, 'Carabobo', 'VE-G'),
(8, 'Cojedes', 'VE-H'),
(9, 'Delta Amacuro', 'VE-Y'),
(10, 'Falc&oacute;n', 'VE-I'),
(11, 'Gu&aacute;rico', 'VE-J'),
(12, 'Lara', 'VE-K'),
(13, 'M&eacute;rida', 'VE-L'),
(14, 'Miranda', 'VE-M'),
(15, 'Monagas', 'VE-N'),
(16, 'Nueva Esparta', 'VE-O'),
(17, 'Portuguesa', 'VE-P'),
(18, 'Sucre', 'VE-R'),
(19, 'T&aacute;chira', 'VE-S'),
(20, 'Trujillo', 'VE-T'),
(21, 'Vargas', 'VE-W'),
(22, 'Yaracuy', 'VE-U'),
(23, 'Zulia', 'VE-V'),
(24, 'Distrito Capital', 'VE-A'),
(25, 'Dependencias Federales', 'VE-Z');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE IF NOT EXISTS `municipio` (
`id_municipio` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `municipio` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=463 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`id_municipio`, `id_estado`, `municipio`) VALUES
(1, 1, 'Alto Orinoco'),
(2, 1, 'Atabapo'),
(3, 1, 'Atures'),
(4, 1, 'Autana'),
(5, 1, 'Manapiare'),
(6, 1, 'Maroa'),
(7, 1, 'R&iacute;o Negro'),
(8, 2, 'Anaco'),
(9, 2, 'Aragua'),
(10, 2, 'Manuel Ezequiel Bruzual'),
(11, 2, 'Diego Bautista Urbaneja'),
(12, 2, 'Fernando Pe&ntilde;alver'),
(13, 2, 'Francisco Del Carmen Carvajal'),
(14, 2, 'General Sir Arthur McGregor'),
(15, 2, 'Guanta'),
(16, 2, 'Independencia'),
(17, 2, 'Jos&eacute; Gregorio Monagas'),
(18, 2, 'Juan Antonio Sotillo'),
(19, 2, 'Juan Manuel Cajigal'),
(20, 2, 'Libertad'),
(21, 2, 'Francisco de Miranda'),
(22, 2, 'Pedro Mar&iacute;a Freites'),
(23, 2, 'P&iacute;ritu'),
(24, 2, 'San Jos&eacute; de Guanipa'),
(25, 2, 'San Juan de Capistrano'),
(26, 2, 'Santa Ana'),
(27, 2, 'Sim&oacute;n Bol&iacute;var'),
(28, 2, 'Sim&oacute;n Rodr&iacute;guez'),
(29, 3, 'Achaguas'),
(30, 3, 'Biruaca'),
(31, 3, 'Mu&ntilde;&oacute;z'),
(32, 3, 'P&aacute;ez'),
(33, 3, 'Pedro Camejo'),
(34, 3, 'R&oacute;mulo Gallegos'),
(35, 3, 'San Fernando'),
(36, 4, 'Atanasio Girardot'),
(37, 4, 'Bol&iacute;var'),
(38, 4, 'Camatagua'),
(39, 4, 'Francisco Linares Alc&aacute;ntara'),
(40, 4, 'Jos&eacute; &Aacute;ngel Lamas'),
(41, 4, 'Jos&eacute; F&eacute;lix Ribas'),
(42, 4, 'Jos&eacute; Rafael Revenga'),
(43, 4, 'Libertador'),
(44, 4, 'Mario Brice&ntilde;o Iragorry'),
(45, 4, 'Ocumare de la Costa de Oro'),
(46, 4, 'San Casimiro'),
(47, 4, 'San Sebasti&aacute;n'),
(48, 4, 'Santiago Mari&ntilde;o'),
(49, 4, 'Santos Michelena'),
(50, 4, 'Sucre'),
(51, 4, 'Tovar'),
(52, 4, 'Urdaneta'),
(53, 4, 'Zamora'),
(54, 5, 'Alberto Arvelo Torrealba'),
(55, 5, 'Andr&eacute;s Eloy Blanco'),
(56, 5, 'Antonio Jos&eacute; de Sucre'),
(57, 5, 'Arismendi'),
(58, 5, 'Barinas'),
(59, 5, 'Bol&iacute;var'),
(60, 5, 'Cruz Paredes'),
(61, 5, 'Ezequiel Zamora'),
(62, 5, 'Obispos'),
(63, 5, 'Pedraza'),
(64, 5, 'Rojas'),
(65, 5, 'Sosa'),
(66, 6, 'Caron&iacute;'),
(67, 6, 'Cede&ntilde;o'),
(68, 6, 'El Callao'),
(69, 6, 'Gran Sabana'),
(70, 6, 'Heres'),
(71, 6, 'Piar'),
(72, 6, 'Angostura (Ra&uacute;l Leoni)'),
(73, 6, 'Roscio'),
(74, 6, 'Sifontes'),
(75, 6, 'Sucre'),
(76, 6, 'Padre Pedro Chien'),
(77, 7, 'Bejuma'),
(78, 7, 'Carlos Arvelo'),
(79, 7, 'Diego Ibarra'),
(80, 7, 'Guacara'),
(81, 7, 'Juan Jos&eacute; Mora'),
(82, 7, 'Libertador'),
(83, 7, 'Los Guayos'),
(84, 7, 'Miranda'),
(85, 7, 'Montalb&aacute;n'),
(86, 7, 'Naguanagua'),
(87, 7, 'Puerto Cabello'),
(88, 7, 'San Diego'),
(89, 7, 'San Joaqu&iacute;n'),
(90, 7, 'Valencia'),
(91, 8, 'Anzo&aacute;tegui'),
(92, 8, 'Tinaquillo'),
(93, 8, 'Girardot'),
(94, 8, 'Lima Blanco'),
(95, 8, 'Pao de San Juan Bautista'),
(96, 8, 'Ricaurte'),
(97, 8, 'R&oacute;mulo Gallegos'),
(98, 8, 'San Carlos'),
(99, 8, 'Tinaco'),
(100, 9, 'Antonio D&iacute;az'),
(101, 9, 'Casacoima'),
(102, 9, 'Pedernales'),
(103, 9, 'Tucupita'),
(104, 10, 'Acosta'),
(105, 10, 'Bol&iacute;var'),
(106, 10, 'Buchivacoa'),
(107, 10, 'Cacique Manaure'),
(108, 10, 'Carirubana'),
(109, 10, 'Colina'),
(110, 10, 'Dabajuro'),
(111, 10, 'Democracia'),
(112, 10, 'Falc&oacute;n'),
(113, 10, 'Federaci&oacute;n'),
(114, 10, 'Jacura'),
(115, 10, 'Jos&eacute; Laurencio Silva'),
(116, 10, 'Los Taques'),
(117, 10, 'Mauroa'),
(118, 10, 'Miranda'),
(119, 10, 'Monse&ntilde;or Iturriza'),
(120, 10, 'Palmasola'),
(121, 10, 'Petit'),
(122, 10, 'P&iacute;ritu'),
(123, 10, 'San Francisco'),
(124, 10, 'Sucre'),
(125, 10, 'Toc&oacute;pero'),
(126, 10, 'Uni&oacute;n'),
(127, 10, 'Urumaco'),
(128, 10, 'Zamora'),
(129, 11, 'Camagu&aacute;n'),
(130, 11, 'Chaguaramas'),
(131, 11, 'El Socorro'),
(132, 11, 'Jos&eacute; F&eacute;lix Ribas'),
(133, 11, 'Jos&eacute; Tadeo Monagas'),
(134, 11, 'Juan Germ&aacute;n Roscio'),
(135, 11, 'Juli&aacute;n Mellado'),
(136, 11, 'Las Mercedes'),
(137, 11, 'Leonardo Infante'),
(138, 11, 'Pedro Zaraza'),
(139, 11, 'Ort&iacute;z'),
(140, 11, 'San Ger&oacute;nimo de Guayabal'),
(141, 11, 'San Jos&eacute; de Guaribe'),
(142, 11, 'Santa Mar&iacute;a de Ipire'),
(143, 11, 'Sebasti&aacute;n Francisco de Miranda'),
(144, 12, 'Andr&eacute;s Eloy Blanco'),
(145, 12, 'Crespo'),
(146, 12, 'Iribarren'),
(147, 12, 'Jim&eacute;nez'),
(148, 12, 'Mor&aacute;n'),
(149, 12, 'Palavecino'),
(150, 12, 'Sim&oacute;n Planas'),
(151, 12, 'Torres'),
(152, 12, 'Urdaneta'),
(179, 13, 'Alberto Adriani'),
(180, 13, 'Andr&eacute;s Bello'),
(181, 13, 'Antonio Pinto Salinas'),
(182, 13, 'Aricagua'),
(183, 13, 'Arzobispo Chac&oacute;n'),
(184, 13, 'Campo El&iacute;as'),
(185, 13, 'Caracciolo Parra Olmedo'),
(186, 13, 'Cardenal Quintero'),
(187, 13, 'Guaraque'),
(188, 13, 'Julio C&eacute;sar Salas'),
(189, 13, 'Justo Brice&ntilde;o'),
(190, 13, 'Libertador'),
(191, 13, 'Miranda'),
(192, 13, 'Obispo Ramos de Lora'),
(193, 13, 'Padre Noguera'),
(194, 13, 'Pueblo Llano'),
(195, 13, 'Rangel'),
(196, 13, 'Rivas D&aacute;vila'),
(197, 13, 'Santos Marquina'),
(198, 13, 'Sucre'),
(199, 13, 'Tovar'),
(200, 13, 'Tulio Febres Cordero'),
(201, 13, 'Zea'),
(223, 14, 'Acevedo'),
(224, 14, 'Andr&eacute;s Bello'),
(225, 14, 'Baruta'),
(226, 14, 'Bri&oacute;n'),
(227, 14, 'Buroz'),
(228, 14, 'Carrizal'),
(229, 14, 'Chacao'),
(230, 14, 'Crist&oacute;bal Rojas'),
(231, 14, 'El Hatillo'),
(232, 14, 'Guaicaipuro'),
(233, 14, 'Independencia'),
(234, 14, 'Lander'),
(235, 14, 'Los Salias'),
(236, 14, 'P&aacute;ez'),
(237, 14, 'Paz Castillo'),
(238, 14, 'Pedro Gual'),
(239, 14, 'Plaza'),
(240, 14, 'Sim&oacute;n Bol&iacute;var'),
(241, 14, 'Sucre'),
(242, 14, 'Urdaneta'),
(243, 14, 'Zamora'),
(258, 15, 'Acosta'),
(259, 15, 'Aguasay'),
(260, 15, 'Bol&iacute;var'),
(261, 15, 'Caripe'),
(262, 15, 'Cede&ntilde;o'),
(263, 15, 'Ezequiel Zamora'),
(264, 15, 'Libertador'),
(265, 15, 'Matur&iacute;n'),
(266, 15, 'Piar'),
(267, 15, 'Punceres'),
(268, 15, 'Santa B&aacute;rbara'),
(269, 15, 'Sotillo'),
(270, 15, 'Uracoa'),
(271, 16, 'Antol&iacute;n del Campo'),
(272, 16, 'Arismendi'),
(273, 16, 'Garc&iacute;a'),
(274, 16, 'G&oacute;mez'),
(275, 16, 'Maneiro'),
(276, 16, 'Marcano'),
(277, 16, 'Mari&ntilde;o'),
(278, 16, 'Pen&iacute;nsula de Macanao'),
(279, 16, 'Tubores'),
(280, 16, 'Villalba'),
(281, 16, 'D&iacute;az'),
(282, 17, 'Agua Blanca'),
(283, 17, 'Araure'),
(284, 17, 'Esteller'),
(285, 17, 'Guanare'),
(286, 17, 'Guanarito'),
(287, 17, 'Monse&ntilde;or Jos&eacute; Vicente de Unda'),
(288, 17, 'Ospino'),
(289, 17, 'P&aacute;ez'),
(290, 17, 'Papel&oacute;n'),
(291, 17, 'San Genaro de Bocono&iacute;to'),
(292, 17, 'San Rafael de Onoto'),
(293, 17, 'Santa Rosal&iacute;a'),
(294, 17, 'Sucre'),
(295, 17, 'Tur&eacute;n'),
(296, 18, 'Andr&eacute;s Eloy Blanco'),
(297, 18, 'Andr&eacute;s Mata'),
(298, 18, 'Arismendi'),
(299, 18, 'Ben&iacute;tez'),
(300, 18, 'Berm&uacute;dez'),
(301, 18, 'Bol&iacute;var'),
(302, 18, 'Cajigal'),
(303, 18, 'Cruz Salmer&oacute;n Acosta'),
(304, 18, 'Libertador'),
(305, 18, 'Mari&ntilde;o'),
(306, 18, 'Mej&iacute;a'),
(307, 18, 'Montes'),
(308, 18, 'Ribero'),
(309, 18, 'Sucre'),
(310, 18, 'Vald&eacute;z'),
(341, 19, 'Andr&eacute;s Bello'),
(342, 19, 'Antonio R&oacute;mulo Costa'),
(343, 19, 'Ayacucho'),
(344, 19, 'Bol&iacute;var'),
(345, 19, 'C&aacute;rdenas'),
(346, 19, 'C&oacute;rdoba'),
(347, 19, 'Fern&aacute;ndez Feo'),
(348, 19, 'Francisco de Miranda'),
(349, 19, 'Garc&iacute;a de Hevia'),
(350, 19, 'Gu&aacute;simos'),
(351, 19, 'Independencia'),
(352, 19, 'J&aacute;uregui'),
(353, 19, 'Jos&eacute; Mar&iacute;a Vargas'),
(354, 19, 'Jun&iacute;n'),
(355, 19, 'Libertad'),
(356, 19, 'Libertador'),
(357, 19, 'Lobatera'),
(358, 19, 'Michelena'),
(359, 19, 'Panamericano'),
(360, 19, 'Pedro Mar&iacute;a Ure&ntilde;a'),
(361, 19, 'Rafael Urdaneta'),
(362, 19, 'Samuel Dar&iacute;o Maldonado'),
(363, 19, 'San Crist&oacute;bal'),
(364, 19, 'Seboruco'),
(365, 19, 'Sim&oacute;n Rodr&iacute;guez'),
(366, 19, 'Sucre'),
(367, 19, 'Torbes'),
(368, 19, 'Uribante'),
(369, 19, 'San Judas Tadeo'),
(370, 20, 'Andr&eacute;s Bello'),
(371, 20, 'Bocon&oacute;'),
(372, 20, 'Bol&iacute;var'),
(373, 20, 'Candelaria'),
(374, 20, 'Carache'),
(375, 20, 'Escuque'),
(376, 20, 'Jos&eacute; Felipe M&aacute;rquez Ca&ntilde;izalez'),
(377, 20, 'Juan Vicente Campos El&iacute;as'),
(378, 20, 'La Ceiba'),
(379, 20, 'Miranda'),
(380, 20, 'Monte Carmelo'),
(381, 20, 'Motat&aacute;n'),
(382, 20, 'Pamp&aacute;n'),
(383, 20, 'Pampanito'),
(384, 20, 'Rafael Rangel'),
(385, 20, 'San Rafael de Carvajal'),
(386, 20, 'Sucre'),
(387, 20, 'Trujillo'),
(388, 20, 'Urdaneta'),
(389, 20, 'Valera'),
(390, 21, 'Vargas'),
(391, 22, 'Ar&iacute;stides Bastidas'),
(392, 22, 'Bol&iacute;var'),
(407, 22, 'Bruzual'),
(408, 22, 'Cocorote'),
(409, 22, 'Independencia'),
(410, 22, 'Jos&eacute; Antonio P&aacute;ez'),
(411, 22, 'La Trinidad'),
(412, 22, 'Manuel Monge'),
(413, 22, 'Nirgua'),
(414, 22, 'Pe&ntilde;a'),
(415, 22, 'San Felipe'),
(416, 22, 'Sucre'),
(417, 22, 'Urachiche'),
(418, 22, 'Jos&eacute; Joaqu&iacute;n Veroes'),
(441, 23, 'Almirante Padilla'),
(442, 23, 'Baralt'),
(443, 23, 'Cabimas'),
(444, 23, 'Catatumbo'),
(445, 23, 'Col&oacute;n'),
(446, 23, 'Francisco Javier Pulgar'),
(447, 23, 'P&aacute;ez'),
(448, 23, 'Jes&uacute;s Enrique Losada'),
(449, 23, 'Jes&uacute;s Mar&iacute;a Sempr&uacute;n'),
(450, 23, 'La Ca&ntilde;ada de Urdaneta'),
(451, 23, 'Lagunillas'),
(452, 23, 'Machiques de Perij&aacute;'),
(453, 23, 'Mara'),
(454, 23, 'Maracaibo'),
(455, 23, 'Miranda'),
(456, 23, 'Rosario de Perij&aacute;'),
(457, 23, 'San Francisco'),
(458, 23, 'Santa Rita'),
(459, 23, 'Sim&oacute;n Bol&iacute;var'),
(460, 23, 'Sucre'),
(461, 23, 'Valmore Rodr&iacute;guez'),
(462, 24, 'Libertador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquia`
--

CREATE TABLE IF NOT EXISTS `parroquia` (
`id_parroquia` int(11) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `parroquia` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1139 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `parroquia`
--

INSERT INTO `parroquia` (`id_parroquia`, `id_municipio`, `parroquia`) VALUES
(1, 1, 'Alto Orinoco'),
(2, 1, 'Huachamacare Acana&ntilde;a'),
(3, 1, 'Marawaka Toky Shamana&ntilde;a'),
(4, 1, 'Mavaka Mavaka'),
(5, 1, 'Sierra Parima Parimab&eacute;'),
(6, 2, 'Ucata Laja Lisa'),
(7, 2, 'Yapacana Macuruco'),
(8, 2, 'Caname Guarinuma'),
(9, 3, 'Fernando Gir&oacute;n Tovar'),
(10, 3, 'Luis Alberto G&oacute;mez'),
(11, 3, 'Pahue&ntilde;a Lim&oacute;n de Parhue&ntilde;a'),
(12, 3, 'Platanillal Platanillal'),
(13, 4, 'Samariapo'),
(14, 4, 'Sipapo'),
(15, 4, 'Munduapo'),
(16, 4, 'Guayapo'),
(17, 5, 'Alto Ventuari'),
(18, 5, 'Medio Ventuari'),
(19, 5, 'Bajo Ventuari'),
(20, 6, 'Victorino'),
(21, 6, 'Comunidad'),
(22, 7, 'Casiquiare'),
(23, 7, 'Cocuy'),
(24, 7, 'San Carlos de R&iacute;o Negro'),
(25, 7, 'Solano'),
(26, 8, 'Anaco'),
(27, 8, 'San Joaqu&iacute;n'),
(28, 9, 'Cachipo'),
(29, 9, 'Aragua de Barcelona'),
(30, 11, 'Lecher&iacute;a'),
(31, 11, 'El Morro'),
(32, 12, 'Puerto P&iacute;ritu'),
(33, 12, 'San Miguel'),
(34, 12, 'Sucre'),
(35, 13, 'Valle de Guanape'),
(36, 13, 'Santa B&aacute;rbara'),
(37, 14, 'El Chaparro'),
(38, 14, 'Tom&aacute;s Alfaro'),
(39, 14, 'Calatrava'),
(40, 15, 'Guanta'),
(41, 15, 'Chorrer&oacute;n'),
(42, 16, 'Mamo'),
(43, 16, 'Soledad'),
(44, 17, 'Mapire'),
(45, 17, 'Piar'),
(46, 17, 'Santa Clara'),
(47, 17, 'San Diego de Cabrutica'),
(48, 17, 'Uverito'),
(49, 17, 'Zuata'),
(50, 18, 'Puerto La Cruz'),
(51, 18, 'Pozuelos'),
(52, 19, 'Onoto'),
(53, 19, 'San Pablo'),
(54, 20, 'San Mateo'),
(55, 20, 'El Carito'),
(56, 20, 'Santa In&eacute;s'),
(57, 20, 'La Romere&ntilde;a'),
(58, 21, 'Atapirire'),
(59, 21, 'Boca del Pao'),
(60, 21, 'El Pao'),
(61, 21, 'Pariagu&aacute;n'),
(62, 22, 'Cantaura'),
(63, 22, 'Libertador'),
(64, 22, 'Santa Rosa'),
(65, 22, 'Urica'),
(66, 23, 'P&iacute;ritu'),
(67, 23, 'San Francisco'),
(68, 24, 'San Jos&eacute; de Guanipa'),
(69, 25, 'Boca de Uchire'),
(70, 25, 'Boca de Ch&aacute;vez'),
(71, 26, 'Pueblo Nuevo'),
(72, 26, 'Santa Ana'),
(73, 27, 'Bergat&iacute;n'),
(74, 27, 'Caigua'),
(75, 27, 'El Carmen'),
(76, 27, 'El Pilar'),
(77, 27, 'Naricual'),
(78, 27, 'San Crsit&oacute;bal'),
(79, 28, 'Edmundo Barrios'),
(80, 28, 'Miguel Otero Silva'),
(81, 29, 'Achaguas'),
(82, 29, 'Apurito'),
(83, 29, 'El Yagual'),
(84, 29, 'Guachara'),
(85, 29, 'Mucuritas'),
(86, 29, 'Queseras del medio'),
(87, 30, 'Biruaca'),
(88, 31, 'Bruzual'),
(89, 31, 'Mantecal'),
(90, 31, 'Quintero'),
(91, 31, 'Rinc&oacute;n Hondo'),
(92, 31, 'San Vicente'),
(93, 32, 'Guasdualito'),
(94, 32, 'Aramendi'),
(95, 32, 'El Amparo'),
(96, 32, 'San Camilo'),
(97, 32, 'Urdaneta'),
(98, 33, 'San Juan de Payara'),
(99, 33, 'Codazzi'),
(100, 33, 'Cunaviche'),
(101, 34, 'Elorza'),
(102, 34, 'La Trinidad'),
(103, 35, 'San Fernando'),
(104, 35, 'El Recreo'),
(105, 35, 'Pe&ntilde;alver'),
(106, 35, 'San Rafael de Atamaica'),
(107, 36, 'Pedro Jos&eacute; Ovalles'),
(108, 36, 'Joaqu&iacute;n Crespo'),
(109, 36, 'Jos&eacute; Casanova Godoy'),
(110, 36, 'Madre Mar&iacute;a de San Jos&eacute;'),
(111, 36, 'Andr&eacute;s Eloy Blanco'),
(112, 36, 'Los Tacarigua'),
(113, 36, 'Las Delicias'),
(114, 36, 'Choron&iacute;'),
(115, 37, 'Bol&iacute;var'),
(116, 38, 'Camatagua'),
(117, 38, 'Carmen de Cura'),
(118, 39, 'Santa Rita'),
(119, 39, 'Francisco de Miranda'),
(120, 39, 'Mose&ntilde;or Feliciano Gonz&aacute;lez'),
(121, 40, 'Santa Cruz'),
(122, 41, 'Jos&eacute; F&eacute;lix Ribas'),
(123, 41, 'Castor Nieves R&iacute;os'),
(124, 41, 'Las Guacamayas'),
(125, 41, 'Pao de Z&aacute;rate'),
(126, 41, 'Zuata'),
(127, 42, 'Jos&eacute; Rafael Revenga'),
(128, 43, 'Palo Negro'),
(129, 43, 'San Mart&iacute;n de Porres'),
(130, 44, 'El Lim&oacute;n'),
(131, 44, 'Ca&ntilde;a de Az&uacute;car'),
(132, 45, 'Ocumare de la Costa'),
(133, 46, 'San Casimiro'),
(134, 46, 'Guiripa'),
(135, 46, 'Ollas de Caramacate'),
(136, 46, 'Valle Mor&iacute;n'),
(137, 47, 'San Sebast&iacute;an'),
(138, 48, 'Turmero'),
(139, 48, 'Arevalo Aponte'),
(140, 48, 'Chuao'),
(141, 48, 'Sam&aacute;n de Guere'),
(142, 48, 'Alfredo Pacheco Miranda'),
(143, 49, 'Santos Michelena'),
(144, 49, 'Tiara'),
(145, 50, 'Cagua'),
(146, 50, 'Bella Vista'),
(147, 51, 'Tovar'),
(148, 52, 'Urdaneta'),
(149, 52, 'Las Pe&ntilde;itas'),
(150, 52, 'San Francisco de Cara'),
(151, 52, 'Taguay'),
(152, 53, 'Zamora'),
(153, 53, 'Magdaleno'),
(154, 53, 'San Francisco de As&iacute;s'),
(155, 53, 'Valles de Tucutunemo'),
(156, 53, 'Augusto Mijares'),
(157, 54, 'Sabaneta'),
(158, 54, 'Juan Antonio Rodr&iacute;guez Dom&iacute;nguez'),
(159, 55, 'El Cant&oacute;n'),
(160, 55, 'Santa Cruz de Guacas'),
(161, 55, 'Puerto Vivas'),
(162, 56, 'Ticoporo'),
(163, 56, 'Nicol&aacute;s Pulido'),
(164, 56, 'Andr&eacute;s Bello'),
(165, 57, 'Arismendi'),
(166, 57, 'Guadarrama'),
(167, 57, 'La Uni&oacute;n'),
(168, 57, 'San Antonio'),
(169, 58, 'Barinas'),
(170, 58, 'Alberto Arvelo Larriva'),
(171, 58, 'San Silvestre'),
(172, 58, 'Santa In&eacute;s'),
(173, 58, 'Santa Luc&iacute;a'),
(174, 58, 'Torumos'),
(175, 58, 'El Carmen'),
(176, 58, 'R&oacute;mulo Betancourt'),
(177, 58, 'Coraz&oacute;n de Jes&uacute;s'),
(178, 58, 'Ram&oacute;n Ignacio M&eacute;ndez'),
(179, 58, 'Alto Barinas'),
(180, 58, 'Manuel Palacio Fajardo'),
(181, 58, 'Juan Antonio Rodr&iacute;guez Dom&iacute;nguez'),
(182, 58, 'Dominga Ortiz de P&aacute;ez'),
(183, 59, 'Barinitas'),
(184, 59, 'Altamira de C&aacute;ceres'),
(185, 59, 'Calderas'),
(186, 60, 'Barrancas'),
(187, 60, 'El Socorro'),
(188, 60, 'Mazparrito'),
(189, 61, 'Santa B&aacute;rbara'),
(190, 61, 'Pedro Brice&ntilde;o M&eacute;ndez'),
(191, 61, 'Ram&oacute;n Ignacio M&eacute;ndez'),
(192, 61, 'Jos&eacute; Ignacio del Pumar'),
(193, 62, 'Obispos'),
(194, 62, 'Guasimitos'),
(195, 62, 'El Real'),
(196, 62, 'La Luz'),
(197, 63, 'Ciudad Bol&iacute;via'),
(198, 63, 'Jos&eacute; Ignacio Brice&ntilde;o'),
(199, 63, 'Jos&eacute; F&eacute;lix Ribas'),
(200, 63, 'P&aacute;ez'),
(201, 64, 'Libertad'),
(202, 64, 'Dolores'),
(203, 64, 'Santa Rosa'),
(204, 64, 'Palacio Fajardo'),
(205, 65, 'Ciudad de Nutrias'),
(206, 65, 'El Regalo'),
(207, 65, 'Puerto Nutrias'),
(208, 65, 'Santa Catalina'),
(209, 66, 'Cachamay'),
(210, 66, 'Chirica'),
(211, 66, 'Dalla Costa'),
(212, 66, 'Once de Abril'),
(213, 66, 'Sim&oacute;n Bol&iacute;var'),
(214, 66, 'Unare'),
(215, 66, 'Universidad'),
(216, 66, 'Vista al Sol'),
(217, 66, 'Pozo Verde'),
(218, 66, 'Yocoima'),
(219, 66, '5 de Julio'),
(220, 67, 'Cede&ntilde;o'),
(221, 67, 'Altagracia'),
(222, 67, 'Ascensi&oacute;n Farreras'),
(223, 67, 'Guaniamo'),
(224, 67, 'La Urbana'),
(225, 67, 'Pijiguaos'),
(226, 68, 'El Callao'),
(227, 69, 'Gran Sabana'),
(228, 69, 'Ikabar&uacute;'),
(229, 70, 'Catedral'),
(230, 70, 'Zea'),
(231, 70, 'Orinoco'),
(232, 70, 'Jos&eacute; Antonio P&aacute;ez'),
(233, 70, 'Marhuanta'),
(234, 70, 'Agua Salada'),
(235, 70, 'Vista Hermosa'),
(236, 70, 'La Sabanita'),
(237, 70, 'Panapana'),
(238, 71, 'Andr&eacute;s Eloy Blanco'),
(239, 71, 'Pedro Cova'),
(240, 72, 'Ra&uacute;l Leoni'),
(241, 72, 'Barceloneta'),
(242, 72, 'Santa B&aacute;rbara'),
(243, 72, 'San Francisco'),
(244, 73, 'Roscio'),
(245, 73, 'Sal&oacute;m'),
(246, 74, 'Sifontes'),
(247, 74, 'Dalla Costa'),
(248, 74, 'San Isidro'),
(249, 75, 'Sucre'),
(250, 75, 'Aripao'),
(251, 75, 'Guarataro'),
(252, 75, 'Las Majadas'),
(253, 75, 'Moitaco'),
(254, 76, 'Padre Pedro Chien'),
(255, 76, 'R&iacute;o Grande'),
(256, 77, 'Bejuma'),
(257, 77, 'Canoabo'),
(258, 77, 'Sim&oacute;n Bol&iacute;var'),
(259, 78, 'Guigue'),
(260, 78, 'Carabobo'),
(261, 78, 'Tacarigua'),
(262, 79, 'Mariara'),
(263, 79, 'Aguas Calientes'),
(264, 80, 'Ciudad Alianza'),
(265, 80, 'Guacara'),
(266, 80, 'Yagua'),
(267, 81, 'Mor&oacute;n'),
(268, 81, 'Yagua'),
(269, 82, 'Tocuyito'),
(270, 82, 'Independencia'),
(271, 83, 'Los Guayos'),
(272, 84, 'Miranda'),
(273, 85, 'Montalb&aacute;n'),
(274, 86, 'Naguanagua'),
(275, 87, 'Bartolom&eacute; Sal&oacute;m'),
(276, 87, 'Democracia'),
(277, 87, 'Fraternidad'),
(278, 87, 'Goaigoaza'),
(279, 87, 'Juan Jos&eacute; Flores'),
(280, 87, 'Uni&oacute;n'),
(281, 87, 'Borburata'),
(282, 87, 'Patanemo'),
(283, 88, 'San Diego'),
(284, 89, 'San Joaqu&iacute;n'),
(285, 90, 'Candelaria'),
(286, 90, 'Catedral'),
(287, 90, 'El Socorro'),
(288, 90, 'Miguel Pe&ntilde;a'),
(289, 90, 'Rafael Urdaneta'),
(290, 90, 'San Blas'),
(291, 90, 'San Jos&eacute;'),
(292, 90, 'Santa Rosa'),
(293, 90, 'Negro Primero'),
(294, 91, 'Cojedes'),
(295, 91, 'Juan de Mata Su&aacute;rez'),
(296, 92, 'Tinaquillo'),
(297, 93, 'El Ba&uacute;l'),
(298, 93, 'Sucre'),
(299, 94, 'La Aguadita'),
(300, 94, 'Macapo'),
(301, 95, 'El Pao'),
(302, 96, 'El Amparo'),
(303, 96, 'Libertad de Cojedes'),
(304, 97, 'R&oacute;mulo Gallegos'),
(305, 98, 'San Carlos de Austria'),
(306, 98, 'Juan &Aacute;ngel Bravo'),
(307, 98, 'Manuel Manrique'),
(308, 99, 'General en Jefe Jos&eacute; Laurencio Silva'),
(309, 100, 'Curiapo'),
(310, 100, 'Almirante Luis Bri&oacute;n'),
(311, 100, 'Francisco Aniceto Lugo'),
(312, 100, 'Manuel Renaud'),
(313, 100, 'Padre Barral'),
(314, 100, 'Santos de Abelgas'),
(315, 101, 'Imataca'),
(316, 101, 'Cinco de Julio'),
(317, 101, 'Juan Bautista Arismendi'),
(318, 101, 'Manuel Piar'),
(319, 101, 'R&oacute;mulo Gallegos'),
(320, 102, 'Pedernales'),
(321, 102, 'Luis Beltr&aacute;n Prieto Figueroa'),
(322, 103, 'San Jos&eacute; (Delta Amacuro)'),
(323, 103, 'Jos&eacute; Vidal Marcano'),
(324, 103, 'Juan Mill&aacute;n'),
(325, 103, 'Leonardo Ru&iacute;z Pineda'),
(326, 103, 'Mariscal Antonio Jos&eacute; de Sucre'),
(327, 103, 'Monse&ntilde;or Argimiro Garc&iacute;a'),
(328, 103, 'San Rafael (Delta Amacuro)'),
(329, 103, 'Virgen del Valle'),
(330, 10, 'Clarines'),
(331, 10, 'Guanape'),
(332, 10, 'Sabana de Uchire'),
(333, 104, 'Capadare'),
(334, 104, 'La Pastora'),
(335, 104, 'Libertador'),
(336, 104, 'San Juan de los Cayos'),
(337, 105, 'Aracua'),
(338, 105, 'La Pe&ntilde;a'),
(339, 105, 'San Luis'),
(340, 106, 'Bariro'),
(341, 106, 'Boroj&oacute;'),
(342, 106, 'Capat&aacute;rida'),
(343, 106, 'Guajiro'),
(344, 106, 'Seque'),
(345, 106, 'Zaz&aacute;rida'),
(346, 106, 'Valle de Eroa'),
(347, 107, 'Cacique Manaure'),
(348, 108, 'Norte'),
(349, 108, 'Carirubana'),
(350, 108, 'Santa Ana'),
(351, 108, 'Urbana Punta Card&oacute;n'),
(352, 109, 'La Vela de Coro'),
(353, 109, 'Acurigua'),
(354, 109, 'Guaibacoa'),
(355, 109, 'Las Calderas'),
(356, 109, 'Macoruca'),
(357, 110, 'Dabajuro'),
(358, 111, 'Agua Clara'),
(359, 111, 'Avaria'),
(360, 111, 'Pedregal'),
(361, 111, 'Piedra Grande'),
(362, 111, 'Purureche'),
(363, 112, 'Adaure'),
(364, 112, 'Ad&iacute;cora'),
(365, 112, 'Baraived'),
(366, 112, 'Buena Vista'),
(367, 112, 'Jadacaquiva'),
(368, 112, 'El V&iacute;nculo'),
(369, 112, 'El Hato'),
(370, 112, 'Moruy'),
(371, 112, 'Pueblo Nuevo'),
(372, 113, 'Agua Larga'),
(373, 113, 'El Pauj&iacute;'),
(374, 113, 'Independencia'),
(375, 113, 'Maparar&iacute;'),
(376, 114, 'Agua Linda'),
(377, 114, 'Araurima'),
(378, 114, 'Jacura'),
(379, 115, 'Tucacas'),
(380, 115, 'Boca de Aroa'),
(381, 116, 'Los Taques'),
(382, 116, 'Judibana'),
(383, 117, 'Mene de Mauroa'),
(384, 117, 'San F&eacute;lix'),
(385, 117, 'Casigua'),
(386, 118, 'Guzm&aacute;n Guillermo'),
(387, 118, 'Mitare'),
(388, 118, 'R&iacute;o Seco'),
(389, 118, 'Sabaneta'),
(390, 118, 'San Antonio'),
(391, 118, 'San Gabriel'),
(392, 118, 'Santa Ana'),
(393, 119, 'Boca del Tocuyo'),
(394, 119, 'Chichiriviche'),
(395, 119, 'Tocuyo de la Costa'),
(396, 120, 'Palmasola'),
(397, 121, 'Cabure'),
(398, 121, 'Colina'),
(399, 121, 'Curimagua'),
(400, 122, 'San Jos&eacute; de la Costa'),
(401, 122, 'P&iacute;ritu'),
(402, 123, 'San Francisco'),
(403, 124, 'Sucre'),
(404, 124, 'Pecaya'),
(405, 125, 'Toc&oacute;pero'),
(406, 126, 'El Charal'),
(407, 126, 'Las Vegas del Tuy'),
(408, 126, 'Santa Cruz de Bucaral'),
(409, 127, 'Bruzual'),
(410, 127, 'Urumaco'),
(411, 128, 'Puerto Cumarebo'),
(412, 128, 'La Ci&eacute;naga'),
(413, 128, 'La Soledad'),
(414, 128, 'Pueblo Cumarebo'),
(415, 128, 'Zaz&aacute;rida'),
(416, 113, 'Churuguara'),
(417, 129, 'Camagu&aacute;n'),
(418, 129, 'Puerto Miranda'),
(419, 129, 'Uverito'),
(420, 130, 'Chaguaramas'),
(421, 131, 'El Socorro'),
(422, 132, 'Tucupido'),
(423, 132, 'San Rafael de Laya'),
(424, 133, 'Altagracia de Orituco'),
(425, 133, 'San Rafael de Orituco'),
(426, 133, 'San Francisco Javier de Lezama'),
(427, 133, 'Paso Real de Macaira'),
(428, 133, 'Carlos Soublette'),
(429, 133, 'San Francisco de Macaira'),
(430, 133, 'Libertad de Orituco'),
(431, 134, 'Cantaclaro'),
(432, 134, 'San Juan de los Morros'),
(433, 134, 'Parapara'),
(434, 135, 'El Sombrero'),
(435, 135, 'Sosa'),
(436, 136, 'Las Mercedes'),
(437, 136, 'Cabruta'),
(438, 136, 'Santa Rita de Manapire'),
(439, 137, 'Valle de la Pascua'),
(440, 137, 'Espino'),
(441, 138, 'San Jos&eacute; de Unare'),
(442, 138, 'Zaraza'),
(443, 139, 'San Jos&eacute; de Tiznados'),
(444, 139, 'San Francisco de Tiznados'),
(445, 139, 'San Lorenzo de Tiznados'),
(446, 139, 'Ortiz'),
(447, 140, 'Guayabal'),
(448, 140, 'Cazorla'),
(449, 141, 'San Jos&eacute; de Guaribe'),
(450, 141, 'Uveral'),
(451, 142, 'Santa Mar&iacute;a de Ipire'),
(452, 142, 'Altamira'),
(453, 143, 'El Calvario'),
(454, 143, 'El Rastro'),
(455, 143, 'Guardatinajas'),
(456, 143, 'Capital Urbana Calabozo'),
(457, 144, 'Quebrada Honda de Guache'),
(458, 144, 'P&iacute;o Tamayo'),
(459, 144, 'Yacamb&uacute;'),
(460, 145, 'Fr&eacute;itez'),
(461, 145, 'Jos&eacute; Mar&iacute;a Blanco'),
(462, 146, 'Catedral'),
(463, 146, 'Concepci&oacute;n'),
(464, 146, 'El Cuj&iacute;'),
(465, 146, 'Juan de Villegas'),
(466, 146, 'Santa Rosa'),
(467, 146, 'Tamaca'),
(468, 146, 'Uni&oacute;n'),
(469, 146, 'Aguedo Felipe Alvarado'),
(470, 146, 'Buena Vista'),
(471, 146, 'Ju&aacute;rez'),
(472, 147, 'Juan Bautista Rodr&iacute;guez'),
(473, 147, 'Cuara'),
(474, 147, 'Diego de Lozada'),
(475, 147, 'Para&iacute;so de San Jos&eacute;'),
(476, 147, 'San Miguel'),
(477, 147, 'Tintorero'),
(478, 147, 'Jos&eacute; Bernardo Dorante'),
(479, 147, 'Coronel Mariano Peraza'),
(480, 148, 'Bol&iacute;var'),
(481, 148, 'Anzo&aacute;tegui'),
(482, 148, 'Guarico'),
(483, 148, 'Hilario Luna y Luna'),
(484, 148, 'Humocaro Alto'),
(485, 148, 'Humocaro Bajo'),
(486, 148, 'La Candelaria'),
(487, 148, 'Mor&aacute;n'),
(488, 149, 'Cabudare'),
(489, 149, 'Jos&eacute; Gregorio Bastidas'),
(490, 149, 'Agua Viva'),
(491, 150, 'Sarare'),
(492, 150, 'Bur&iacute;a'),
(493, 150, 'Gustavo Vegas Le&oacute;n'),
(494, 151, 'Trinidad Samuel'),
(495, 151, 'Antonio D&iacute;az'),
(496, 151, 'Camacaro'),
(497, 151, 'Casta&ntilde;eda'),
(498, 151, 'Cecilio Zubillaga'),
(499, 151, 'Chiquinquir&aacute;'),
(500, 151, 'El Blanco'),
(501, 151, 'Espinoza de los Monteros'),
(502, 151, 'Lara'),
(503, 151, 'Las Mercedes'),
(504, 151, 'Manuel Morillo'),
(505, 151, 'Monta&ntilde;a Verde'),
(506, 151, 'Montes de Oca'),
(507, 151, 'Torres'),
(508, 151, 'Heriberto Arroyo'),
(509, 151, 'Reyes Vargas'),
(510, 151, 'Altagracia'),
(511, 152, 'Siquisique'),
(512, 152, 'Moroturo'),
(513, 152, 'San Miguel'),
(514, 152, 'Xaguas'),
(515, 179, 'Presidente Betancourt'),
(516, 179, 'Presidente P&aacute;ez'),
(517, 179, 'Presidente R&oacute;mulo Gallegos'),
(518, 179, 'Gabriel Pic&oacute;n Gonz&aacute;lez'),
(519, 179, 'H&eacute;ctor Amable Mora'),
(520, 179, 'Jos&eacute; Nucete Sardi'),
(521, 179, 'Pulido M&eacute;ndez'),
(522, 180, 'La Azulita'),
(523, 181, 'Santa Cruz de Mora'),
(524, 181, 'Mesa Bol&iacute;var'),
(525, 181, 'Mesa de Las Palmas'),
(526, 182, 'Aricagua'),
(527, 182, 'San Antonio'),
(528, 183, 'Canagua'),
(529, 183, 'Capur&iacute;'),
(530, 183, 'Chacant&aacute;'),
(531, 183, 'El Molino'),
(532, 183, 'Guaimaral'),
(533, 183, 'Mucutuy'),
(534, 183, 'Mucuchach&iacute;'),
(535, 184, 'Fern&aacute;ndez Pe&ntilde;a'),
(536, 184, 'Matriz'),
(537, 184, 'Montalb&aacute;n'),
(538, 184, 'Acequias'),
(539, 184, 'Jaj&iacute;'),
(540, 184, 'La Mesa'),
(541, 184, 'San Jos&eacute; del Sur'),
(542, 185, 'Tucan&iacute;'),
(543, 185, 'Florencio Ram&iacute;rez'),
(544, 186, 'Santo Domingo'),
(545, 186, 'Las Piedras'),
(546, 187, 'Guaraque'),
(547, 187, 'Mesa de Quintero'),
(548, 187, 'R&iacute;o Negro'),
(549, 188, 'Arapuey'),
(550, 188, 'Palmira'),
(551, 189, 'San Crist&oacute;bal de Torondoy'),
(552, 189, 'Torondoy'),
(553, 190, 'Antonio Spinetti Dini'),
(554, 190, 'Arias'),
(555, 190, 'Caracciolo Parra P&eacute;rez'),
(556, 190, 'Domingo Pe&ntilde;a'),
(557, 190, 'El Llano'),
(558, 190, 'Gonzalo Pic&oacute;n Febres'),
(559, 190, 'Jacinto Plaza'),
(560, 190, 'Juan Rodr&iacute;guez Su&aacute;rez'),
(561, 190, 'Lasso de la Vega'),
(562, 190, 'Mariano Pic&oacute;n Salas'),
(563, 190, 'Milla'),
(564, 190, 'Osuna Rodr&iacute;guez'),
(565, 190, 'Sagrario'),
(566, 190, 'El Morro'),
(567, 190, 'Los Nevados'),
(568, 191, 'Andr&eacute;s Eloy Blanco'),
(569, 191, 'La Venta'),
(570, 191, 'Pi&ntilde;ango'),
(571, 191, 'Timotes'),
(572, 192, 'Eloy Paredes'),
(573, 192, 'San Rafael de Alc&aacute;zar'),
(574, 192, 'Santa Elena de Arenales'),
(575, 193, 'Santa Mar&iacute;a de Caparo'),
(576, 194, 'Pueblo Llano'),
(577, 195, 'Cacute'),
(578, 195, 'La Toma'),
(579, 195, 'Mucuch&iacute;es'),
(580, 195, 'Mucurub&aacute;'),
(581, 195, 'San Rafael'),
(582, 196, 'Ger&oacute;nimo Maldonado'),
(583, 196, 'Bailadores'),
(584, 197, 'Tabay'),
(585, 198, 'Chiguar&aacute;'),
(586, 198, 'Est&aacute;nquez'),
(587, 198, 'Lagunillas'),
(588, 198, 'La Trampa'),
(589, 198, 'Pueblo Nuevo del Sur'),
(590, 198, 'San Juan'),
(591, 199, 'El Amparo'),
(592, 199, 'El Llano'),
(593, 199, 'San Francisco'),
(594, 199, 'Tovar'),
(595, 200, 'Independencia'),
(596, 200, 'Mar&iacute;a de la Concepci&oacute;n Palacios Blan'),
(597, 200, 'Nueva Bolivia'),
(598, 200, 'Santa Apolonia'),
(599, 201, 'Ca&ntilde;o El Tigre'),
(600, 201, 'Zea'),
(601, 223, 'Araguita'),
(602, 223, 'Ar&eacute;valo Gonz&aacute;lez'),
(603, 223, 'Capaya'),
(604, 223, 'Caucagua'),
(605, 223, 'Panaquire'),
(606, 223, 'Ribas'),
(607, 223, 'El Caf&eacute;'),
(608, 223, 'Marizapa'),
(609, 224, 'Cumbo'),
(610, 224, 'San Jos&eacute; de Barlovento'),
(611, 225, 'El Cafetal'),
(612, 225, 'Las Minas'),
(613, 225, 'Nuestra Se&ntilde;ora del Rosario'),
(614, 226, 'Higuerote'),
(615, 226, 'Curiepe'),
(616, 226, 'Tacarigua de Bri&oacute;n'),
(617, 227, 'Mamporal'),
(618, 228, 'Carrizal'),
(619, 229, 'Chacao'),
(620, 230, 'Charallave'),
(621, 230, 'Las Brisas'),
(622, 231, 'El Hatillo'),
(623, 232, 'Altagracia de la Monta&ntilde;a'),
(624, 232, 'Cecilio Acosta'),
(625, 232, 'Los Teques'),
(626, 232, 'El Jarillo'),
(627, 232, 'San Pedro'),
(628, 232, 'T&aacute;cata'),
(629, 232, 'Paracotos'),
(630, 233, 'Cartanal'),
(631, 233, 'Santa Teresa del Tuy'),
(632, 234, 'La Democracia'),
(633, 234, 'Ocumare del Tuy'),
(634, 234, 'Santa B&aacute;rbara'),
(635, 235, 'San Antonio de los Altos'),
(636, 236, 'R&iacute;o Chico'),
(637, 236, 'El Guapo'),
(638, 236, 'Tacarigua de la Laguna'),
(639, 236, 'Paparo'),
(640, 236, 'San Fernando del Guapo'),
(641, 237, 'Santa Luc&iacute;a del Tuy'),
(642, 238, 'C&uacute;pira'),
(643, 238, 'Machurucuto'),
(644, 239, 'Guarenas'),
(645, 240, 'San Antonio de Yare'),
(646, 240, 'San Francisco de Yare'),
(647, 241, 'Leoncio Mart&iacute;nez'),
(648, 241, 'Petare'),
(649, 241, 'Caucaguita'),
(650, 241, 'Filas de Mariche'),
(651, 241, 'La Dolorita'),
(652, 242, 'C&uacute;a'),
(653, 242, 'Nueva C&uacute;a'),
(654, 243, 'Guatire'),
(655, 243, 'Bol&iacute;var'),
(656, 258, 'San Antonio de Matur&iacute;n'),
(657, 258, 'San Francisco de Matur&iacute;n'),
(658, 259, 'Aguasay'),
(659, 260, 'Caripito'),
(660, 261, 'El Gu&aacute;charo'),
(661, 261, 'La Guanota'),
(662, 261, 'Sabana de Piedra'),
(663, 261, 'San Agust&iacute;n'),
(664, 261, 'Teresen'),
(665, 261, 'Caripe'),
(666, 262, 'Areo'),
(667, 262, 'Capital Cede&ntilde;o'),
(668, 262, 'San F&eacute;lix de Cantalicio'),
(669, 262, 'Viento Fresco'),
(670, 263, 'El Tejero'),
(671, 263, 'Punta de Mata'),
(672, 264, 'Chaguaramas'),
(673, 264, 'Las Alhuacas'),
(674, 264, 'Tabasca'),
(675, 264, 'Temblador'),
(676, 265, 'Alto de los Godos'),
(677, 265, 'Boquer&oacute;n'),
(678, 265, 'Las Cocuizas'),
(679, 265, 'La Cruz'),
(680, 265, 'San Sim&oacute;n'),
(681, 265, 'El Corozo'),
(682, 265, 'El Furrial'),
(683, 265, 'Jusep&iacute;n'),
(684, 265, 'La Pica'),
(685, 265, 'San Vicente'),
(686, 266, 'Aparicio'),
(687, 266, 'Aragua de Matur&iacute;n'),
(688, 266, 'Chaguamal'),
(689, 266, 'El Pinto'),
(690, 266, 'Guanaguana'),
(691, 266, 'La Toscana'),
(692, 266, 'Taguaya'),
(693, 267, 'Cachipo'),
(694, 267, 'Quiriquire'),
(695, 268, 'Santa B&aacute;rbara'),
(696, 269, 'Barrancas'),
(697, 269, 'Los Barrancos de Fajardo'),
(698, 270, 'Uracoa'),
(699, 271, 'Antol&iacute;n del Campo'),
(700, 272, 'Arismendi'),
(701, 273, 'Garc&iacute;a'),
(702, 273, 'Francisco Fajardo'),
(703, 274, 'Bol&iacute;var'),
(704, 274, 'Guevara'),
(705, 274, 'Matasiete'),
(706, 274, 'Santa Ana'),
(707, 274, 'Sucre'),
(708, 275, 'Aguirre'),
(709, 275, 'Maneiro'),
(710, 276, 'Adri&aacute;n'),
(711, 276, 'Juan Griego'),
(712, 276, 'Yaguaraparo'),
(713, 277, 'Porlamar'),
(714, 278, 'San Francisco de Macanao'),
(715, 278, 'Boca de R&iacute;o'),
(716, 279, 'Tubores'),
(717, 279, 'Los Baleales'),
(718, 280, 'Vicente Fuentes'),
(719, 280, 'Villalba'),
(720, 281, 'San Juan Bautista'),
(721, 281, 'Zabala'),
(722, 283, 'Capital Araure'),
(723, 283, 'R&iacute;o Acarigua'),
(724, 284, 'Capital Esteller'),
(725, 284, 'Uveral'),
(726, 285, 'Guanare'),
(727, 285, 'C&oacute;rdoba'),
(728, 285, 'San Jos&eacute; de la Monta&ntilde;a'),
(729, 285, 'San Juan de Guanaguanare'),
(730, 285, 'Virgen de la Coromoto'),
(731, 286, 'Guanarito'),
(732, 286, 'Trinidad de la Capilla'),
(733, 286, 'Divina Pastora'),
(734, 287, 'Monse&ntilde;or Jos&eacute; Vicente de Unda'),
(735, 287, 'Pe&ntilde;a Blanca'),
(736, 288, 'Capital Ospino'),
(737, 288, 'Aparici&oacute;n'),
(738, 288, 'La Estaci&oacute;n'),
(739, 289, 'P&aacute;ez'),
(740, 289, 'Payara'),
(741, 289, 'Pimpinela'),
(742, 289, 'Ram&oacute;n Peraza'),
(743, 290, 'Papel&oacute;n'),
(744, 290, 'Ca&ntilde;o Delgadito'),
(745, 291, 'San Genaro de Boconoito'),
(746, 291, 'Antol&iacute;n Tovar'),
(747, 292, 'San Rafael de Onoto'),
(748, 292, 'Santa Fe'),
(749, 292, 'Thermo Morles'),
(750, 293, 'Santa Rosal&iacute;a'),
(751, 293, 'Florida'),
(752, 294, 'Sucre'),
(753, 294, 'Concepci&oacute;n'),
(754, 294, 'San Rafael de Palo Alzado'),
(755, 294, 'Uvencio Antonio Vel&aacute;squez'),
(756, 294, 'San Jos&eacute; de Saguaz'),
(757, 294, 'Villa Rosa'),
(758, 295, 'Tur&eacute;n'),
(759, 295, 'Canelones'),
(760, 295, 'Santa Cruz'),
(761, 295, 'San Isidro Labrador'),
(762, 296, 'Mari&ntilde;o'),
(763, 296, 'R&oacute;mulo Gallegos'),
(764, 297, 'San Jos&eacute; de Aerocuar'),
(765, 297, 'Tavera Acosta'),
(766, 298, 'R&iacute;o Caribe'),
(767, 298, 'Antonio Jos&eacute; de Sucre'),
(768, 298, 'El Morro de Puerto Santo'),
(769, 298, 'Puerto Santo'),
(770, 298, 'San Juan de las Galdonas'),
(771, 299, 'El Pilar'),
(772, 299, 'El Rinc&oacute;n'),
(773, 299, 'General Francisco Antonio V&aacute;quez'),
(774, 299, 'Guara&uacute;nos'),
(775, 299, 'Tunapuicito'),
(776, 299, 'Uni&oacute;n'),
(777, 300, 'Santa Catalina'),
(778, 300, 'Santa Rosa'),
(779, 300, 'Santa Teresa'),
(780, 300, 'Bol&iacute;var'),
(781, 300, 'Maracapana'),
(782, 302, 'Libertad'),
(783, 302, 'El Paujil'),
(784, 302, 'Yaguaraparo'),
(785, 303, 'Cruz Salmer&oacute;n Acosta'),
(786, 303, 'Chacopata'),
(787, 303, 'Manicuare'),
(788, 304, 'Tunapuy'),
(789, 304, 'Campo El&iacute;as'),
(790, 305, 'Irapa'),
(791, 305, 'Campo Claro'),
(792, 305, 'Maraval'),
(793, 305, 'San Antonio de Irapa'),
(794, 305, 'Soro'),
(795, 306, 'Mej&iacute;a'),
(796, 307, 'Cumanacoa'),
(797, 307, 'Arenas'),
(798, 307, 'Aricagua'),
(799, 307, 'Cogollar'),
(800, 307, 'San Fernando'),
(801, 307, 'San Lorenzo'),
(802, 308, 'Villa Frontado (Muelle de Cariaco)'),
(803, 308, 'Catuaro'),
(804, 308, 'Rend&oacute;n'),
(805, 308, 'San Cruz'),
(806, 308, 'Santa Mar&iacute;a'),
(807, 309, 'Altagracia'),
(808, 309, 'Santa In&eacute;s'),
(809, 309, 'Valent&iacute;n Valiente'),
(810, 309, 'Ayacucho'),
(811, 309, 'San Juan'),
(812, 309, 'Ra&uacute;l Leoni'),
(813, 309, 'Gran Mariscal'),
(814, 310, 'Crist&oacute;bal Col&oacute;n'),
(815, 310, 'Bideau'),
(816, 310, 'Punta de Piedras'),
(817, 310, 'Guiria'),
(818, 341, 'Andr&eacute;s Bello'),
(819, 342, 'Antonio R&oacute;mulo Costa'),
(820, 343, 'Ayacucho'),
(821, 343, 'Rivas Berti'),
(822, 343, 'San Pedro del R&iacute;o'),
(823, 344, 'Bol&iacute;var'),
(824, 344, 'Palotal'),
(825, 344, 'General Juan Vicente G&oacute;mez'),
(826, 344, 'Isa&iacute;as Medina Angarita'),
(827, 345, 'C&aacute;rdenas'),
(828, 345, 'Amenodoro &Aacute;ngel Lamus'),
(829, 345, 'La Florida'),
(830, 346, 'C&oacute;rdoba'),
(831, 347, 'Fern&aacute;ndez Feo'),
(832, 347, 'Alberto Adriani'),
(833, 347, 'Santo Domingo'),
(834, 348, 'Francisco de Miranda'),
(835, 349, 'Garc&iacute;a de Hevia'),
(836, 349, 'Boca de Grita'),
(837, 349, 'Jos&eacute; Antonio P&aacute;ez'),
(838, 350, 'Gu&aacute;simos'),
(839, 351, 'Independencia'),
(840, 351, 'Juan Germ&aacute;n Roscio'),
(841, 351, 'Rom&aacute;n C&aacute;rdenas'),
(842, 352, 'J&aacute;uregui'),
(843, 352, 'Emilio Constantino Guerrero'),
(844, 352, 'Monse&ntilde;or Miguel Antonio Salas'),
(845, 353, 'Jos&eacute; Mar&iacute;a Vargas'),
(846, 354, 'Jun&iacute;n'),
(847, 354, 'La Petr&oacute;lea'),
(848, 354, 'Quinimar&iacute;'),
(849, 354, 'Bram&oacute;n'),
(850, 355, 'Libertad'),
(851, 355, 'Cipriano Castro'),
(852, 355, 'Manuel Felipe Rugeles'),
(853, 356, 'Libertador'),
(854, 356, 'Doradas'),
(855, 356, 'Emeterio Ochoa'),
(856, 356, 'San Joaqu&iacute;n de Navay'),
(857, 357, 'Lobatera'),
(858, 357, 'Constituci&oacute;n'),
(859, 358, 'Michelena'),
(860, 359, 'Panamericano'),
(861, 359, 'La Palmita'),
(862, 360, 'Pedro Mar&iacute;a Ure&ntilde;a'),
(863, 360, 'Nueva Arcadia'),
(864, 361, 'Delicias'),
(865, 361, 'Pecaya'),
(866, 362, 'Samuel Dar&iacute;o Maldonado'),
(867, 362, 'Bocon&oacute;'),
(868, 362, 'Hern&aacute;ndez'),
(869, 363, 'La Concordia'),
(870, 363, 'San Juan Bautista'),
(871, 363, 'Pedro Mar&iacute;a Morantes'),
(872, 363, 'San Sebasti&aacute;n'),
(873, 363, 'Dr. Francisco Romero Lobo'),
(874, 364, 'Seboruco'),
(875, 365, 'Sim&oacute;n Rodr&iacute;guez'),
(876, 366, 'Sucre'),
(877, 366, 'Eleazar L&oacute;pez Contreras'),
(878, 366, 'San Pablo'),
(879, 367, 'Torbes'),
(880, 368, 'Uribante'),
(881, 368, 'C&aacute;rdenas'),
(882, 368, 'Juan Pablo Pe&ntilde;alosa'),
(883, 368, 'Potos&iacute;'),
(884, 369, 'San Judas Tadeo'),
(885, 370, 'Araguaney'),
(886, 370, 'El Jaguito'),
(887, 370, 'La Esperanza'),
(888, 370, 'Santa Isabel'),
(889, 371, 'Bocon&oacute;'),
(890, 371, 'El Carmen'),
(891, 371, 'Mosquey'),
(892, 371, 'Ayacucho'),
(893, 371, 'Burbusay'),
(894, 371, 'General Ribas'),
(895, 371, 'Guaramacal'),
(896, 371, 'Vega de Guaramacal'),
(897, 371, 'Monse&ntilde;or J&aacute;uregui'),
(898, 371, 'Rafael Rangel'),
(899, 371, 'San Miguel'),
(900, 371, 'San Jos&eacute;'),
(901, 372, 'Sabana Grande'),
(902, 372, 'Cheregu&eacute;'),
(903, 372, 'Granados'),
(904, 373, 'Arnoldo Gabald&oacute;n'),
(905, 373, 'Bolivia'),
(906, 373, 'Carrillo'),
(907, 373, 'Cegarra'),
(908, 373, 'Chejend&eacute;'),
(909, 373, 'Manuel Salvador Ulloa'),
(910, 373, 'San Jos&eacute;'),
(911, 374, 'Carache'),
(912, 374, 'La Concepci&oacute;n'),
(913, 374, 'Cuicas'),
(914, 374, 'Panamericana'),
(915, 374, 'Santa Cruz'),
(916, 375, 'Escuque'),
(917, 375, 'La Uni&oacute;n'),
(918, 375, 'Santa Rita'),
(919, 375, 'Sabana Libre'),
(920, 376, 'El Socorro'),
(921, 376, 'Los Caprichos'),
(922, 376, 'Antonio Jos&eacute; de Sucre'),
(923, 377, 'Campo El&iacute;as'),
(924, 377, 'Arnoldo Gabald&oacute;n'),
(925, 378, 'Santa Apolonia'),
(926, 378, 'El Progreso'),
(927, 378, 'La Ceiba'),
(928, 378, 'Tres de Febrero'),
(929, 379, 'El Dividive'),
(930, 379, 'Agua Santa'),
(931, 379, 'Agua Caliente'),
(932, 379, 'El Cenizo'),
(933, 379, 'Valerita'),
(934, 380, 'Monte Carmelo'),
(935, 380, 'Buena Vista'),
(936, 380, 'Santa Mar&iacute;a del Horc&oacute;n'),
(937, 381, 'Motat&aacute;n'),
(938, 381, 'El Ba&ntilde;o'),
(939, 381, 'Jalisco'),
(940, 382, 'Pamp&aacute;n'),
(941, 382, 'Flor de Patria'),
(942, 382, 'La Paz'),
(943, 382, 'Santa Ana'),
(944, 383, 'Pampanito'),
(945, 383, 'La Concepci&oacute;n'),
(946, 383, 'Pampanito II'),
(947, 384, 'Betijoque'),
(948, 384, 'Jos&eacute; Gregorio Hern&aacute;ndez'),
(949, 384, 'La Pueblita'),
(950, 384, 'Los Cedros'),
(951, 385, 'Carvajal'),
(952, 385, 'Campo Alegre'),
(953, 385, 'Antonio Nicol&aacute;s Brice&ntilde;o'),
(954, 385, 'Jos&eacute; Leonardo Su&aacute;rez'),
(955, 386, 'Sabana de Mendoza'),
(956, 386, 'Jun&iacute;n'),
(957, 386, 'Valmore Rodr&iacute;guez'),
(958, 386, 'El Para&iacute;so'),
(959, 387, 'Andr&eacute;s Linares'),
(960, 387, 'Chiquinquir&aacute;'),
(961, 387, 'Crist&oacute;bal Mendoza'),
(962, 387, 'Cruz Carrillo'),
(963, 387, 'Matriz'),
(964, 387, 'Monse&ntilde;or Carrillo'),
(965, 387, 'Tres Esquinas'),
(966, 388, 'Cabimb&uacute;'),
(967, 388, 'Jaj&oacute;'),
(968, 388, 'La Mesa de Esnujaque'),
(969, 388, 'Santiago'),
(970, 388, 'Tu&ntilde;ame'),
(971, 388, 'La Quebrada'),
(972, 389, 'Juan Ignacio Montilla'),
(973, 389, 'La Beatriz'),
(974, 389, 'La Puerta'),
(975, 389, 'Mendoza del Valle de Momboy'),
(976, 389, 'Mercedes D&iacute;az'),
(977, 389, 'San Luis'),
(978, 390, 'Caraballeda'),
(979, 390, 'Carayaca'),
(980, 390, 'Carlos Soublette'),
(981, 390, 'Caruao Chuspa'),
(982, 390, 'Catia La Mar'),
(983, 390, 'El Junko'),
(984, 390, 'La Guaira'),
(985, 390, 'Macuto'),
(986, 390, 'Maiquet&iacute;a'),
(987, 390, 'Naiguat&aacute;'),
(988, 390, 'Urimare'),
(989, 391, 'Ar&iacute;stides Bastidas'),
(990, 392, 'Bol&iacute;var'),
(991, 407, 'Chivacoa'),
(992, 407, 'Campo El&iacute;as'),
(993, 408, 'Cocorote'),
(994, 409, 'Independencia'),
(995, 410, 'Jos&eacute; Antonio P&aacute;ez'),
(996, 411, 'La Trinidad'),
(997, 412, 'Manuel Monge'),
(998, 413, 'Sal&oacute;m'),
(999, 413, 'Temerla'),
(1000, 413, 'Nirgua'),
(1001, 414, 'San Andr&eacute;s'),
(1002, 414, 'Yaritagua'),
(1003, 415, 'San Javier'),
(1004, 415, 'Albarico'),
(1005, 415, 'San Felipe'),
(1006, 416, 'Sucre'),
(1007, 417, 'Urachiche'),
(1008, 418, 'El Guayabo'),
(1009, 418, 'Farriar'),
(1010, 441, 'Isla de Toas'),
(1011, 441, 'Monagas'),
(1012, 442, 'San Timoteo'),
(1013, 442, 'General Urdaneta'),
(1014, 442, 'Libertador'),
(1015, 442, 'Marcelino Brice&ntilde;o'),
(1016, 442, 'Pueblo Nuevo'),
(1017, 442, 'Manuel Guanipa Matos'),
(1018, 443, 'Ambrosio'),
(1019, 443, 'Carmen Herrera'),
(1020, 443, 'La Rosa'),
(1021, 443, 'Germ&aacute;n R&iacute;os Linares'),
(1022, 443, 'San Benito'),
(1023, 443, 'R&oacute;mulo Betancourt'),
(1024, 443, 'Jorge Hern&aacute;ndez'),
(1025, 443, 'Punta Gorda'),
(1026, 443, 'Ar&iacute;stides Calvani'),
(1027, 444, 'Encontrados'),
(1028, 444, 'Ud&oacute;n P&eacute;rez'),
(1029, 445, 'Moralito'),
(1030, 445, 'San Carlos del Zulia'),
(1031, 445, 'Santa Cruz del Zulia'),
(1032, 445, 'Santa B&aacute;rbara'),
(1033, 445, 'Urribarr&iacute;'),
(1034, 446, 'Carlos Quevedo'),
(1035, 446, 'Francisco Javier Pulgar'),
(1036, 446, 'Sim&oacute;n Rodr&iacute;guez'),
(1037, 446, 'Guamo-Gavilanes'),
(1038, 448, 'La Concepci&oacute;n'),
(1039, 448, 'San Jos&eacute;'),
(1040, 448, 'Mariano Parra Le&oacute;n'),
(1041, 448, 'Jos&eacute; Ram&oacute;n Y&eacute;pez'),
(1042, 449, 'Jes&uacute;s Mar&iacute;a Sempr&uacute;n'),
(1043, 449, 'Bar&iacute;'),
(1044, 450, 'Concepci&oacute;n'),
(1045, 450, 'Andr&eacute;s Bello'),
(1046, 450, 'Chiquinquir&aacute;'),
(1047, 450, 'El Carmelo'),
(1048, 450, 'Potreritos'),
(1049, 451, 'Libertad'),
(1050, 451, 'Alonso de Ojeda'),
(1051, 451, 'Venezuela'),
(1052, 451, 'Eleazar L&oacute;pez Contreras'),
(1053, 451, 'Campo Lara'),
(1054, 452, 'Bartolom&eacute; de las Casas'),
(1055, 452, 'Libertad'),
(1056, 452, 'R&iacute;o Negro'),
(1057, 452, 'San Jos&eacute; de Perij&aacute;'),
(1058, 453, 'San Rafael'),
(1059, 453, 'La Sierrita'),
(1060, 453, 'Las Parcelas'),
(1061, 453, 'Luis de Vicente'),
(1062, 453, 'Monse&ntilde;or Marcos Sergio Godoy'),
(1063, 453, 'Ricaurte'),
(1064, 453, 'Tamare'),
(1065, 454, 'Antonio Borjas Romero'),
(1066, 454, 'Bol&iacute;var'),
(1067, 454, 'Cacique Mara'),
(1068, 454, 'Carracciolo Parra P&eacute;rez'),
(1069, 454, 'Cecilio Acosta'),
(1070, 454, 'Cristo de Aranza'),
(1071, 454, 'Coquivacoa'),
(1072, 454, 'Chiquinquir&aacute;'),
(1073, 454, 'Francisco Eugenio Bustamante'),
(1074, 454, 'Idelfonzo V&aacute;squez'),
(1075, 454, 'Juana de &Aacute;vila'),
(1076, 454, 'Luis Hurtado Higuera'),
(1077, 454, 'Manuel Dagnino'),
(1078, 454, 'Olegario Villalobos'),
(1079, 454, 'Ra&uacute;l Leoni'),
(1080, 454, 'Santa Luc&iacute;a'),
(1081, 454, 'Venancio Pulgar'),
(1082, 454, 'San Isidro'),
(1083, 455, 'Altagracia'),
(1084, 455, 'Far&iacute;a'),
(1085, 455, 'Ana Mar&iacute;a Campos'),
(1086, 455, 'San Antonio'),
(1087, 455, 'San Jos&eacute;'),
(1088, 456, 'Donaldo Garc&iacute;a'),
(1089, 456, 'El Rosario'),
(1090, 456, 'Sixto Zambrano'),
(1091, 457, 'San Francisco'),
(1092, 457, 'El Bajo'),
(1093, 457, 'Domitila Flores'),
(1094, 457, 'Francisco Ochoa'),
(1095, 457, 'Los Cortijos'),
(1096, 457, 'Marcial Hern&aacute;ndez'),
(1097, 458, 'Santa Rita'),
(1098, 458, 'El Mene'),
(1099, 458, 'Pedro Lucas Urribarr&iacute;'),
(1100, 458, 'Jos&eacute; Cenobio Urribarr&iacute;'),
(1101, 459, 'Rafael Maria Baralt'),
(1102, 459, 'Manuel Manrique'),
(1103, 459, 'Rafael Urdaneta'),
(1104, 460, 'Bobures'),
(1105, 460, 'Gibraltar'),
(1106, 460, 'Heras'),
(1107, 460, 'Monse&ntilde;or Arturo &Aacute;lvarez'),
(1108, 460, 'R&oacute;mulo Gallegos'),
(1109, 460, 'El Batey'),
(1110, 461, 'Rafael Urdaneta'),
(1111, 461, 'La Victoria'),
(1112, 461, 'Ra&uacute;l Cuenca'),
(1113, 447, 'Sinamaica'),
(1114, 447, 'Alta Guajira'),
(1115, 447, 'El&iacute;as S&aacute;nchez Rubio'),
(1116, 447, 'Guajira'),
(1117, 462, 'Altagracia'),
(1118, 462, 'Ant&iacute;mano'),
(1119, 462, 'Caricuao'),
(1120, 462, 'Catedral'),
(1121, 462, 'Coche'),
(1122, 462, 'El Junquito'),
(1123, 462, 'El Para&iacute;so'),
(1124, 462, 'El Recreo'),
(1125, 462, 'El Valle'),
(1126, 462, 'La Candelaria'),
(1127, 462, 'La Pastora'),
(1128, 462, 'La Vega'),
(1129, 462, 'Macarao'),
(1130, 462, 'San Agust&iacute;n'),
(1131, 462, 'San Bernardino'),
(1132, 462, 'San Jos&eacute;'),
(1133, 462, 'San Juan'),
(1134, 462, 'San Pedro'),
(1135, 462, 'Santa Rosal&iacute;a'),
(1136, 462, 'Santa Teresa'),
(1137, 462, 'Sucre (Catia)'),
(1138, 462, '23 de enero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisologia`
--

CREATE TABLE IF NOT EXISTS `permisologia` (
`id_permisologia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `clave` varchar(250) NOT NULL,
  `tipo` varchar(40) NOT NULL,
  `status` enum('Habilitado','Deshabilitado') NOT NULL,
  `encargado_registro` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `permisologia`
--

INSERT INTO `permisologia` (`id_permisologia`, `id_usuario`, `clave`, `tipo`, `status`, `encargado_registro`, `fecha_registro`) VALUES
(1, 1, '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Administrador', 'Habilitado', 1, '2018-07-02 11:30:27'),
(5, 9, '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Administrador', 'Habilitado', 9, '2018-07-11 13:10:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubro`
--

CREATE TABLE IF NOT EXISTS `rubro` (
`id_rubro` int(11) NOT NULL,
  `id_clasificacion` int(11) NOT NULL,
  `rubro` varchar(200) NOT NULL,
  `status` enum('Habilitado','Deshabilitado') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rubro`
--

INSERT INTO `rubro` (`id_rubro`, `id_clasificacion`, `rubro`, `status`) VALUES
(1, 1, 'Maiz', 'Habilitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
`id_usuario` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `sexo` enum('Masculino','Femenino') NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `telefono1` varchar(20) DEFAULT NULL,
  `telefono2` varchar(20) DEFAULT NULL,
  `ccp` int(11) DEFAULT NULL,
  `scp` int(11) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `cedula`, `nombres`, `sexo`, `fecha_nacimiento`, `telefono1`, `telefono2`, `ccp`, `scp`, `correo`, `fecha_registro`) VALUES
(1, 'V-20376542', 'Javier Antonio', 'Masculino', '2008-06-12', '0426-000-0000', '', 111333, 111222888, 'gregorio@censo.com', '2018-07-02 14:26:21'),
(9, 'V-4497851', 'AVIGAIL JOSE AVILA BRITO', 'Masculino', '1956-04-20', '0426-580-8282', '', 120391239, 2147483647, '', '2018-07-11 13:10:54');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `censo`
--
ALTER TABLE `censo`
 ADD PRIMARY KEY (`id_censo`), ADD KEY `encargado_registro` (`encargado_registro`);

--
-- Indices de la tabla `censo_comuna`
--
ALTER TABLE `censo_comuna`
 ADD PRIMARY KEY (`id_censo_comuna`), ADD KEY `id_censo` (`id_censo`,`id_comuna`), ADD KEY `id_comuna` (`id_comuna`);

--
-- Indices de la tabla `censo_comuna_consejo`
--
ALTER TABLE `censo_comuna_consejo`
 ADD PRIMARY KEY (`id_censo_comuna_consejo`), ADD KEY `id_censo_comuna` (`id_censo_comuna`,`id_censo_consejo`), ADD KEY `id_censo_consejo` (`id_censo_consejo`);

--
-- Indices de la tabla `censo_comuna_coordinador`
--
ALTER TABLE `censo_comuna_coordinador`
 ADD PRIMARY KEY (`id_censo_comuna_coordinador`), ADD KEY `id_permisologia` (`id_permisologia`,`id_censo_comuna`,`encargado_registro`), ADD KEY `id_censo_comuna` (`id_censo_comuna`), ADD KEY `encargado_registro` (`encargado_registro`);

--
-- Indices de la tabla `censo_consejo`
--
ALTER TABLE `censo_consejo`
 ADD PRIMARY KEY (`id_censo_consejo`), ADD KEY `id_censo` (`id_censo`), ADD KEY `id_consejo` (`id_consejo`);

--
-- Indices de la tabla `censo_consejo_coordinador`
--
ALTER TABLE `censo_consejo_coordinador`
 ADD PRIMARY KEY (`id_censo_consejo_coordinador`), ADD KEY `id_permisologia` (`id_permisologia`,`id_censo_consejo`,`encargado_registro`), ADD KEY `id_consejo` (`id_censo_consejo`), ADD KEY `encargado_registro` (`encargado_registro`);

--
-- Indices de la tabla `censo_productor`
--
ALTER TABLE `censo_productor`
 ADD PRIMARY KEY (`id_productor`), ADD KEY `id_usuario` (`id_usuario`), ADD KEY `id_censo` (`id_censo_consejo`), ADD KEY `id_rubro` (`id_rubro`), ADD KEY `encargado_registro` (`encargado_registro`);

--
-- Indices de la tabla `censo_productor_siembra`
--
ALTER TABLE `censo_productor_siembra`
 ADD PRIMARY KEY (`id_siembra`), ADD KEY `id_rubro` (`id_rubro`), ADD KEY `id_productor` (`id_productor`);

--
-- Indices de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
 ADD PRIMARY KEY (`id_clasificacion`);

--
-- Indices de la tabla `comuna`
--
ALTER TABLE `comuna`
 ADD PRIMARY KEY (`id_comuna`), ADD KEY `id_parroquia` (`id_parroquia`,`encargado_registro`), ADD KEY `encargado_registro` (`encargado_registro`), ADD KEY `id_municipio` (`id_municipio`);

--
-- Indices de la tabla `consejo`
--
ALTER TABLE `consejo`
 ADD PRIMARY KEY (`id_consejo`), ADD KEY `id_tipo` (`id_tipo`), ADD KEY `id_tipo_2` (`id_tipo`), ADD KEY `encargado_rgistro` (`encargado_rgistro`), ADD KEY `id_parroquia` (`id_parroquia`);

--
-- Indices de la tabla `consejo_tipo`
--
ALTER TABLE `consejo_tipo`
 ADD PRIMARY KEY (`id_consejo_tipo`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
 ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `municipio`
--
ALTER TABLE `municipio`
 ADD PRIMARY KEY (`id_municipio`), ADD KEY `municipios_id_estado_foreign` (`id_estado`);

--
-- Indices de la tabla `parroquia`
--
ALTER TABLE `parroquia`
 ADD PRIMARY KEY (`id_parroquia`), ADD KEY `parroquias_id_municipio_foreign` (`id_municipio`);

--
-- Indices de la tabla `permisologia`
--
ALTER TABLE `permisologia`
 ADD PRIMARY KEY (`id_permisologia`), ADD KEY `id_usuario` (`id_usuario`), ADD KEY `encargado_registro` (`encargado_registro`);

--
-- Indices de la tabla `rubro`
--
ALTER TABLE `rubro`
 ADD PRIMARY KEY (`id_rubro`), ADD KEY `id_clasificacion` (`id_clasificacion`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
 ADD PRIMARY KEY (`id_usuario`), ADD UNIQUE KEY `cedula` (`cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `censo`
--
ALTER TABLE `censo`
MODIFY `id_censo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `censo_comuna`
--
ALTER TABLE `censo_comuna`
MODIFY `id_censo_comuna` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `censo_comuna_consejo`
--
ALTER TABLE `censo_comuna_consejo`
MODIFY `id_censo_comuna_consejo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `censo_comuna_coordinador`
--
ALTER TABLE `censo_comuna_coordinador`
MODIFY `id_censo_comuna_coordinador` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `censo_consejo`
--
ALTER TABLE `censo_consejo`
MODIFY `id_censo_consejo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `censo_consejo_coordinador`
--
ALTER TABLE `censo_consejo_coordinador`
MODIFY `id_censo_consejo_coordinador` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `censo_productor`
--
ALTER TABLE `censo_productor`
MODIFY `id_productor` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `censo_productor_siembra`
--
ALTER TABLE `censo_productor_siembra`
MODIFY `id_siembra` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
MODIFY `id_clasificacion` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `comuna`
--
ALTER TABLE `comuna`
MODIFY `id_comuna` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `consejo`
--
ALTER TABLE `consejo`
MODIFY `id_consejo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `consejo_tipo`
--
ALTER TABLE `consejo_tipo`
MODIFY `id_consejo_tipo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=463;
--
-- AUTO_INCREMENT de la tabla `parroquia`
--
ALTER TABLE `parroquia`
MODIFY `id_parroquia` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1139;
--
-- AUTO_INCREMENT de la tabla `permisologia`
--
ALTER TABLE `permisologia`
MODIFY `id_permisologia` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `rubro`
--
ALTER TABLE `rubro`
MODIFY `id_rubro` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `censo`
--
ALTER TABLE `censo`
ADD CONSTRAINT `censo_ibfk_1` FOREIGN KEY (`encargado_registro`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `censo_comuna`
--
ALTER TABLE `censo_comuna`
ADD CONSTRAINT `censo_comuna_ibfk_1` FOREIGN KEY (`id_censo`) REFERENCES `censo` (`id_censo`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `censo_comuna_ibfk_2` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id_comuna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `censo_comuna_consejo`
--
ALTER TABLE `censo_comuna_consejo`
ADD CONSTRAINT `censo_comuna_consejo_ibfk_1` FOREIGN KEY (`id_censo_comuna`) REFERENCES `censo_comuna` (`id_censo_comuna`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `censo_comuna_consejo_ibfk_2` FOREIGN KEY (`id_censo_consejo`) REFERENCES `censo_consejo` (`id_censo_consejo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `censo_comuna_coordinador`
--
ALTER TABLE `censo_comuna_coordinador`
ADD CONSTRAINT `censo_comuna_coordinador_ibfk_1` FOREIGN KEY (`id_permisologia`) REFERENCES `permisologia` (`id_permisologia`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `censo_comuna_coordinador_ibfk_2` FOREIGN KEY (`id_censo_comuna`) REFERENCES `censo_comuna` (`id_censo_comuna`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `censo_comuna_coordinador_ibfk_3` FOREIGN KEY (`encargado_registro`) REFERENCES `permisologia` (`id_permisologia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `censo_consejo`
--
ALTER TABLE `censo_consejo`
ADD CONSTRAINT `censo_consejo_ibfk_1` FOREIGN KEY (`id_censo`) REFERENCES `censo` (`id_censo`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `censo_consejo_ibfk_2` FOREIGN KEY (`id_consejo`) REFERENCES `consejo` (`id_consejo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `censo_consejo_coordinador`
--
ALTER TABLE `censo_consejo_coordinador`
ADD CONSTRAINT `censo_consejo_coordinador_ibfk_1` FOREIGN KEY (`id_permisologia`) REFERENCES `permisologia` (`id_permisologia`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `censo_consejo_coordinador_ibfk_2` FOREIGN KEY (`id_censo_consejo`) REFERENCES `censo_consejo` (`id_censo_consejo`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `censo_consejo_coordinador_ibfk_3` FOREIGN KEY (`encargado_registro`) REFERENCES `permisologia` (`id_permisologia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `censo_productor`
--
ALTER TABLE `censo_productor`
ADD CONSTRAINT `censo_productor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `censo_productor_ibfk_3` FOREIGN KEY (`id_rubro`) REFERENCES `rubro` (`id_rubro`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `censo_productor_ibfk_4` FOREIGN KEY (`encargado_registro`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `censo_productor_ibfk_5` FOREIGN KEY (`id_censo_consejo`) REFERENCES `censo_consejo` (`id_censo_consejo`);

--
-- Filtros para la tabla `censo_productor_siembra`
--
ALTER TABLE `censo_productor_siembra`
ADD CONSTRAINT `censo_productor_siembra_ibfk_1` FOREIGN KEY (`id_productor`) REFERENCES `censo_productor` (`id_productor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `comuna`
--
ALTER TABLE `comuna`
ADD CONSTRAINT `comuna_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id_parroquia`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `comuna_ibfk_2` FOREIGN KEY (`encargado_registro`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `comuna_ibfk_3` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id_municipio`);

--
-- Filtros para la tabla `consejo`
--
ALTER TABLE `consejo`
ADD CONSTRAINT `consejo_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `consejo_tipo` (`id_consejo_tipo`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `consejo_ibfk_2` FOREIGN KEY (`encargado_rgistro`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `consejo_ibfk_3` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id_parroquia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `municipio`
--
ALTER TABLE `municipio`
ADD CONSTRAINT `municipio_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `parroquia`
--
ALTER TABLE `parroquia`
ADD CONSTRAINT `parroquia_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id_municipio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permisologia`
--
ALTER TABLE `permisologia`
ADD CONSTRAINT `permisologia_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `permisologia_ibfk_2` FOREIGN KEY (`encargado_registro`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rubro`
--
ALTER TABLE `rubro`
ADD CONSTRAINT `rubro_ibfk_1` FOREIGN KEY (`id_clasificacion`) REFERENCES `clasificacion` (`id_clasificacion`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
