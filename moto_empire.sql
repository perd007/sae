-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 01-07-2013 a las 01:27:57
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `moto_empire`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

CREATE TABLE IF NOT EXISTS `almacen` (
  `id_almacen` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `factura_compra` varchar(11) DEFAULT NULL,
  `factura_venta` varchar(10) DEFAULT NULL,
  `serie_venta` varchar(10) DEFAULT NULL,
  `cantida` int(11) DEFAULT NULL,
  `transaccion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_almacen`),
  KEY `factura_compra` (`factura_compra`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=897 ;

--
-- Volcado de datos para la tabla `almacen`
--

INSERT INTO `almacen` (`id_almacen`, `id_producto`, `factura_compra`, `factura_venta`, `serie_venta`, `cantida`, `transaccion`) VALUES
(883, 1, '000001', NULL, NULL, 38, 'COMPRA'),
(884, 1, NULL, '000001', '000001', 1, 'DEVOLUCION'),
(885, NULL, NULL, NULL, NULL, NULL, 'COMPRA'),
(886, 1, NULL, NULL, NULL, 5, 'EXTRAIDO'),
(887, 1, NULL, '000002', '000001', 1, 'VENTA'),
(888, 1, NULL, '000003', '000001', 2, 'VENTA'),
(889, 1, NULL, '000004', '000001', 2, 'VENTA'),
(890, 1, NULL, '000005', '000001', 1, 'VENTA'),
(891, 1, NULL, '000006', '000001', 1, 'VENTA'),
(892, 1, NULL, '000007', '000001', 1, 'VENTA'),
(893, 1, NULL, '000008', '000001', 1, 'VENTA'),
(894, 1, NULL, '000009', '000001', 1, 'VENTA'),
(895, 1, '000002', NULL, NULL, 2, 'COMPRA-MODIFICADA'),
(896, 2, '000002', NULL, NULL, 1, 'COMPRA-MODIFICADA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE IF NOT EXISTS `caja` (
  `id_caja` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_ap` datetime DEFAULT NULL,
  `fecha_ci` datetime DEFAULT NULL,
  `estado` varchar(11) NOT NULL,
  PRIMARY KEY (`id_caja`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id_caja`, `fecha_ap`, `fecha_ci`, `estado`) VALUES
(1, '2013-06-30 18:33:47', NULL, 'ABIERTA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `id_clientes` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(30) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL,
  `cedula` varchar(11) NOT NULL,
  `direccion` varchar(300) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_clientes`),
  UNIQUE KEY `cedula_UNIQUE` (`cedula`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='aqui se registraras los datos de los clientes del negocio' AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_clientes`, `nombres`, `telefono`, `cedula`, `direccion`, `tipo`) VALUES
(1, 'jose perdomo', '04161423360', '17792270', 'urb la bolivaraina', 'Juridica'),
(2, 'Torres Pedro', '0416254777', '16548895', NULL, 'Natural'),
(4, 'diosa canales', '02485213344', '1654321', 'fffffffffffffffffffffffff', 'Natural'),
(5, 'Mayela Gomez', '02485213344', '19352269', 'hhhhh', 'Natural');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE IF NOT EXISTS `compras` (
  `id_compras` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `num_fac` varchar(11) DEFAULT NULL,
  `proveedor` varchar(20) DEFAULT NULL,
  `fiador` varchar(20) DEFAULT NULL,
  `fecha_limite` date DEFAULT NULL,
  `estatus` varchar(10) DEFAULT NULL,
  `subtotal` decimal(11,2) DEFAULT NULL,
  `iva` decimal(11,2) DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL,
  PRIMARY KEY (`id_compras`),
  KEY `num_fac` (`num_fac`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='se empleara para guardar todas las compras realizadas por la empresa' AUTO_INCREMENT=28 ;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compras`, `fecha`, `num_fac`, `proveedor`, `fiador`, `fecha_limite`, `estatus`, `subtotal`, `iva`, `total`) VALUES
(25, '2013-06-21', '000001', 'J-40035821-3', NULL, NULL, '', NULL, NULL, NULL),
(26, '2013-06-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, '2013-06-27', '000002', 'J-40035821-3', NULL, NULL, ' ', 4.00, 0.48, 4.48);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_productos`
--

CREATE TABLE IF NOT EXISTS `compra_productos` (
  `id_compra_productos` int(11) NOT NULL AUTO_INCREMENT,
  `producto` int(11) NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  `factura` varchar(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `transaccion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_compra_productos`),
  KEY `factura` (`factura`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Volcado de datos para la tabla `compra_productos`
--

INSERT INTO `compra_productos` (`id_compra_productos`, `producto`, `precio`, `factura`, `cantidad`, `transaccion`) VALUES
(21, 1, 540.00, '000001', 38, 'INGRESO POR REGISTRO'),
(22, 1, 2.00, '000002', 2, 'INGRESO POR REGISTRO'),
(23, 2, 200.00, '000002', 1, 'INGRESO POR REGISTRO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE IF NOT EXISTS `empleados` (
  `id_empleado` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` int(11) DEFAULT NULL,
  `nombres` varchar(45) DEFAULT NULL,
  `cargo` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_empleado`),
  KEY `cedula` (`cedula`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `cedula`, `nombres`, `cargo`, `direccion`, `telefono`) VALUES
(1, 16789876, 'Mayela Gomez', 'secretaria', 'sssss', '04267893321');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE IF NOT EXISTS `facturas` (
  `id_facturas` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` varchar(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `serie` varchar(10) NOT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `sub_total` float DEFAULT NULL,
  `iva` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `transaccion` varchar(20) NOT NULL,
  PRIMARY KEY (`id_facturas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id_facturas`, `id_cliente`, `fecha`, `serie`, `numero`, `sub_total`, `iva`, `total`, `transaccion`) VALUES
(19, '17792270', '2013-06-21 13:29:48', '000001', '000001', 1320, 180, 1500, 'DEVOLUCION'),
(20, '17792270', '2013-06-22 00:53:50', '000001', '000002', 1320, 180, 1500, 'FACTURADO'),
(21, '1654321', '2013-06-22 01:12:03', '000001', '000003', 2640, 360, 3000, 'FACTURADO'),
(22, '17792270', '2013-06-22 01:21:49', '000001', '000004', 2640, 360, 3000, 'FACTURADO'),
(23, '17792270', '2013-06-22 01:25:09', '000001', '000005', 1320, 180, 1500, 'FACTURADO'),
(24, '17792270', '2013-06-22 01:26:30', '000001', '000006', 1320, 180, 1500, 'FACTURADO'),
(25, '17792270', '2013-06-22 01:32:21', '000001', '000007', 1320, 180, 1500, 'FACTURADO'),
(26, '17792270', '2013-06-22 01:33:43', '000001', '000008', 1320, 180, 1500, 'FACTURADO'),
(27, '17792270', '2013-06-22 02:30:38', '000001', '000009', 1320, 180, 1500, 'FACTURADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedidos` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `id_cliente` varchar(11) DEFAULT NULL,
  `correlativo` varchar(100) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `iva` decimal(11,2) NOT NULL,
  `subtotal` decimal(11,2) NOT NULL,
  PRIMARY KEY (`id_pedidos`),
  KEY `correlativo` (`correlativo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedidos`, `fecha`, `id_cliente`, `correlativo`, `total`, `iva`, `subtotal`) VALUES
(1, '2013-06-30 18:36:31', '17792270', 'NPedido1', 666.00, 79.92, 586.08);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_productos`
--

CREATE TABLE IF NOT EXISTS `pedido_productos` (
  `id_pedido_productos` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_pedido` varchar(100) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(11,2) NOT NULL,
  PRIMARY KEY (`id_pedido_productos`),
  KEY `id_pedido` (`id_pedido`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `pedido_productos`
--

INSERT INTO `pedido_productos` (`id_pedido_productos`, `id_producto`, `id_pedido`, `cantidad`, `precio`) VALUES
(1, 2, 'NPedido1', 1, 666.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_usuarios`
--

CREATE TABLE IF NOT EXISTS `permisos_usuarios` (
  `id_permisos_usuarios` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `p` int(11) DEFAULT '0',
  `f` int(11) DEFAULT '0',
  `c` int(11) DEFAULT '0',
  `a` int(11) DEFAULT '0',
  `v` int(11) DEFAULT '0',
  `r` int(11) DEFAULT '0',
  `cl` int(11) DEFAULT '0',
  `prv` int(11) DEFAULT '0',
  `s` int(11) DEFAULT '0',
  `u` int(11) DEFAULT '0',
  `ac` int(11) DEFAULT '0',
  `cc` int(11) DEFAULT '0',
  PRIMARY KEY (`id_permisos_usuarios`,`id_usuario`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `permisos_usuarios`
--

INSERT INTO `permisos_usuarios` (`id_permisos_usuarios`, `id_usuario`, `p`, `f`, `c`, `a`, `v`, `r`, `cl`, `prv`, `s`, `u`, `ac`, `cc`) VALUES
(1, 16789876, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios`
--

CREATE TABLE IF NOT EXISTS `precios` (
  `id_precios` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `precio` float DEFAULT NULL,
  PRIMARY KEY (`id_precios`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Volcado de datos para la tabla `precios`
--

INSERT INTO `precios` (`id_precios`, `id_producto`, `fecha`, `hora`, `precio`) VALUES
(5, 1, '2013-06-18', '00:00:00', 898),
(6, 1, '2013-06-18', '00:00:00', 0),
(7, 2, '2013-06-18', '00:00:00', 45),
(8, 2, '2013-06-18', '00:00:00', 45),
(9, 2, '2013-06-18', '00:00:00', 500),
(10, 2, '2013-06-18', '00:00:00', 658.44),
(11, 2, '2013-06-18', '00:00:00', 789),
(12, 2, '2013-06-18', '00:00:00', 666),
(13, 1, '2013-06-18', '00:00:00', 500),
(14, 1, '2013-06-18', '00:00:00', 1500.34);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `probar`
--

CREATE TABLE IF NOT EXISTS `probar` (
  `cod` varchar(6) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serie` varchar(6) NOT NULL,
  `falso` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `probar`
--

INSERT INTO `probar` (`cod`, `id`, `serie`, `falso`) VALUES
('999999', 1, '000001', 'falso'),
('999999', 2, '000001', 'falso'),
('999998', 3, '000002', 'falso'),
('999999', 4, '000002', 'falso'),
('956545', 5, '000003', 'falso'),
('999999', 6, '000003', 'falso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE IF NOT EXISTS `productos` (
  `id_productos` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(10) DEFAULT NULL,
  `marca` varchar(45) DEFAULT NULL,
  `modelo` varchar(45) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `serial` varchar(30) DEFAULT NULL,
  `chasis` varchar(30) DEFAULT NULL,
  `motor` varchar(40) DEFAULT NULL,
  `placa` varchar(10) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_productos`),
  UNIQUE KEY `codigo_UNIQUE` (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_productos`, `tipo`, `marca`, `modelo`, `nombre`, `codigo`, `serial`, `chasis`, `motor`, `placa`, `ano`, `descripcion`) VALUES
(1, 'Repuesto', 'yanaha', 'numero 40', 'rolineras', '00001', NULL, NULL, NULL, NULL, 0, 'cauchos tipo rustico color amarillos'),
(2, 'Repuesto', 'yamaha', 'numero 49', 'rin de moto', '00002', NULL, NULL, NULL, NULL, 0, ''),
(3, 'Repuesto', 'bera', 'Nkl', 'volante', '00003', '01445', NULL, NULL, NULL, 0, NULL),
(4, 'Vehiculo', NULL, 'BR150-2', 'MOTO TIPO PASEO', NULL, NULL, 'QWWWWWWW', 'BRFGGGGGGGGG ', 'AEJ400', 2013, 'MOTO COLOR BLANCA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE IF NOT EXISTS `proveedor` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,
  `rif` varchar(12) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `tipo` varchar(10) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `direccion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `rif`, `nombre`, `tipo`, `telefono`, `direccion`) VALUES
(1, 'J-40035821-3', 'INNOVACIONES INFORMATICA J.P, C.A', 'CONTADO', '04267893321', 'av. la guardia, frente al banco provincial Puerto Ayacucho estado Amazonas'),
(2, 'j-4005555-2', 'CRIDITOS  ORIENTE C.A', 'CREDITO', '55555555555', 'avenida Casanova Godia Maracay estado Aragua');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguros`
--

CREATE TABLE IF NOT EXISTS `seguros` (
  `id_seguro` int(11) NOT NULL AUTO_INCREMENT,
  `cliente` varchar(20) NOT NULL,
  `marca` varchar(20) NOT NULL,
  `modelo` varchar(20) NOT NULL,
  `chasis` varchar(20) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `ano` int(11) NOT NULL,
  `motor` varchar(20) NOT NULL,
  `color` varchar(20) NOT NULL,
  PRIMARY KEY (`id_seguro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuarios` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) DEFAULT NULL,
  `login` varchar(45) DEFAULT NULL,
  `clave` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_usuarios`),
  KEY `id_empleado` (`id_empleado`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuarios`, `id_empleado`, `login`, `clave`) VALUES
(1, 16789876, 'mgomez', '12345');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_productos`
--

CREATE TABLE IF NOT EXISTS `ventas_productos` (
  `id_ventas_productos` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `factura` varchar(10) DEFAULT NULL,
  `serie` varchar(10) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(11,2) DEFAULT NULL,
  `transaccion` varchar(20) NOT NULL DEFAULT 'VENDIDO',
  PRIMARY KEY (`id_ventas_productos`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3081 ;

--
-- Volcado de datos para la tabla `ventas_productos`
--

INSERT INTO `ventas_productos` (`id_ventas_productos`, `id_producto`, `factura`, `serie`, `cantidad`, `precio`, `transaccion`) VALUES
(3072, 1, '000001', '000001', 1, 1500.00, 'DEVUELTO'),
(3073, 1, '000002', '000001', 1, 1500.00, 'VENDIDO'),
(3074, 1, '000003', '000001', 2, 1500.00, 'VENDIDO'),
(3075, 1, '000004', '000001', 2, 1500.00, 'VENDIDO'),
(3076, 1, '000005', '000001', 1, 1500.00, 'VENDIDO'),
(3077, 1, '000006', '000001', 1, 1500.00, 'VENDIDO'),
(3078, 1, '000007', '000001', 1, 1500.00, 'VENDIDO'),
(3079, 1, '000008', '000001', 1, 1500.00, 'VENDIDO'),
(3080, 1, '000009', '000001', 1, 1500.00, 'VENDIDO');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `almacen`
--
ALTER TABLE `almacen`
  ADD CONSTRAINT `almacen_ibfk_1` FOREIGN KEY (`factura_compra`) REFERENCES `compras` (`num_fac`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compra_productos`
--
ALTER TABLE `compra_productos`
  ADD CONSTRAINT `compra_productos_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `compras` (`num_fac`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  ADD CONSTRAINT `pedido_productos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`correlativo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permisos_usuarios`
--
ALTER TABLE `permisos_usuarios`
  ADD CONSTRAINT `permisos_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `empleados` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
