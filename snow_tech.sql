-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2022 a las 03:44:47
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `cedula` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` text NOT NULL,
  `dateadd` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `cedula`, `nombre`, `telefono`, `direccion`, `dateadd`, `usuario_id`, `estatus`) VALUES
(1, 4565456, 'Willian Aquino', '0985419815', 'Mil Viviendas', '2022-08-31 14:03:19', 1, 1),
(2, 5444854, 'Edgar Baez', '485444485', 'San José Obrero', '2022-09-01 10:47:21', 1, 1),
(3, 45456456, 'Hector Villalba', '09416486', 'B° Sirena', '2022-09-17 09:26:41', 1, 1),
(4, 4845645, 'David Aranda', '096345866', 'B° Maria Graciela', '2022-09-17 09:27:21', 1, 1),
(5, 122222, 'Carlos', '0972406538', 'San Ignacio', '2022-10-14 18:34:11', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `correlativo` bigint(11) NOT NULL,
  `no_factura` bigint(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `no_factura` bigint(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `correlativo` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`correlativo`, `cod_producto`, `fecha`, `cantidad`, `precio`, `usuario_id`) VALUES
(1, 1, '0000-00-00 00:00:00', 150, '80000.00', 1),
(2, 2, '2022-09-16 18:29:53', 100, '1500000.00', 2),
(3, 3, '2022-10-13 18:44:47', 0, '0.00', 1),
(4, 4, '2022-10-25 23:05:17', 0, '0.00', 1),
(5, 5, '2022-10-25 23:06:50', 200, '58000.00', 1),
(6, 6, '2022-10-25 23:08:26', 50, '150000.00', 1),
(7, 7, '2022-10-27 10:00:56', 55, '58000.00', 1),
(8, 8, '2022-10-27 10:42:27', 50, '58000.00', 1),
(9, 9, '2022-10-27 10:48:55', 50, '58000.00', 1),
(10, 10, '2022-10-27 10:56:48', 20, '84000.00', 1),
(11, 11, '2022-10-27 11:02:02', 10, '15000.00', 1),
(12, 12, '2022-10-27 12:29:21', 456, '58000.00', 1),
(13, 13, '2022-10-27 12:33:23', 130, '58000.00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `no_factura` bigint(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `usuario` int(11) NOT NULL,
  `cod_cliente` int(11) NOT NULL,
  `total_factura` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `cod_producto` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `proveedor` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `existencia` int(11) NOT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`cod_producto`, `descripcion`, `proveedor`, `precio`, `existencia`, `date_add`, `usuario_id`, `estatus`, `foto`) VALUES
(1, 'Mouse USB', 1, '82000.00', 250, '2022-09-16 18:27:04', 1, 1, 'img_producto.png'),
(2, 'Monitor LCD', 1, '1500000.00', 100, '2022-09-16 18:29:53', 2, 1, 'img_producto.png'),
(3, '', 4, '0.00', 0, '2022-10-13 18:44:47', 1, 1, 'img_producto.png'),
(4, '', 4, '0.00', 0, '2022-10-25 23:05:17', 1, 1, 'img_producto.png'),
(5, 'Pantalla LG', 4, '58000.00', 200, '2022-10-25 23:06:50', 1, 1, 'img_producto.png'),
(6, 'teclado', 3, '150000.00', 50, '2022-10-25 23:08:26', 1, 1, 'img_producto.png'),
(7, 'Pantalla LG', 1, '58000.00', 55, '2022-10-27 10:00:56', 1, 1, 'img_producto.png'),
(8, 'Play Station 5', 1, '58000.00', 50, '2022-10-27 10:42:27', 1, 0, 'img_133dfd2a3797bc539339df744aff5caejpg'),
(9, 'Play Station 5', 1, '58000.00', 50, '2022-10-27 10:48:55', 1, 1, 'img_903a6b2ce0622003767f03856e2c7743.jpg'),
(10, 'Pendrive Sandisk 64Gb', 1, '84000.00', 20, '2022-10-27 10:56:48', 1, 1, 'img_a3317cae44ff614dada03fedf5d76db3.jpg'),
(11, 'Mousepad', 1, '15000.00', 10, '2022-10-27 11:02:02', 1, 1, 'img_producto.png'),
(12, 'hdrd', 3, '58000.00', 456, '2022-10-27 12:29:21', 1, 1, 'img_producto.png'),
(13, 'teclado', 4, '58000.00', 130, '2022-10-27 12:33:23', 1, 1, 'img_c15ffdc2b64472d4321305fc6d336487.jpg');

--
-- Disparadores `producto`
--
DELIMITER $$
CREATE TRIGGER `entradas_A_I` AFTER INSERT ON `producto` FOR EACH ROW BEGIN
		INSERT INTO entradas (cod_producto, cantidad, precio, usuario_id)
		VALUES (new.cod_producto, new.existencia, new.precio, new.usuario_id);
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `cod_proveedor` int(11) NOT NULL,
  `proveedor` varchar(100) NOT NULL,
  `contacto` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` text NOT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`cod_proveedor`, `proveedor`, `contacto`, `telefono`, `direccion`, `date_add`, `usuario_id`, `estatus`) VALUES
(1, 'Big Center', 'Noelia Escobar', '0975515418', 'Asuncion', '2022-09-13 00:14:39', 1, 1),
(2, 'PC House', 'Maria', '0985446458', 'B° San Antonio', '2022-09-17 08:41:51', 1, 1),
(3, 'Informática 1000', 'Patricia Mir', '0945485254', 'Mil Viviendas', '2022-09-17 08:49:28', 1, 1),
(4, 'Kingston', 'Marta Sanabria', '0974841485', 'Asuncion', '2022-09-17 09:30:40', 1, 1),
(5, 'Big Center', 'Marcela', '8787878', 'Ayolas', '2022-10-14 18:38:32', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `rol` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `correo`, `usuario`, `clave`, `rol`, `estatus`) VALUES
(1, 'Jose Villar', 'info@josevillar.com', 'admin', 'c1fea270c48e8079d8ddf7d06d26ab52', 1, 1),
(2, 'Micaela Bustamante', 'micabusta@gmail.com', 'mica', '202cb962ac59075b964b07152d234b70', 2, 1),
(3, 'Guillermo Arguello', 'memoarg@gmail.com', 'Memo', '202cb962ac59075b964b07152d234b70', 2, 1),
(4, 'Claro ', 'clarovillar@gmail.com', 'clar', '202cb962ac59075b964b07152d234b70', 3, 1),
(5, 'Nadia Martinez', 'nadiamar@gmail.com', 'nadia56', '202cb962ac59075b964b07152d234b70', 2, 1),
(7, 'Luis Herrera', 'lucho@gmail.com', 'lucho', '202cb962ac59075b964b07152d234b70', 2, 1),
(8, 'Liz Rossana', 'lizross82@gmail.com', 'rossana82', '202cb962ac59075b964b07152d234b70', 3, 1),
(9, 'Eduardo Forte', 'edu@gmail.com', 'edu', '202cb962ac59075b964b07152d234b70', 3, 1),
(10, 'Marcelo Fretes', 'marcefre@gmail.com', 'marce', '202cb962ac59075b964b07152d234b70', 2, 1),
(11, 'Federico Monterrei', 'federmonter@gmail.com', 'fede', '202cb962ac59075b964b07152d234b70', 3, 1),
(12, 'Sebastian Espinola', 'espinolasebas@gmail.com', 'sebas48', '202cb962ac59075b964b07152d234b70', 3, 1),
(13, 'Carlos Vasquez', 'carlos74vasquez@gmail.com', 'carlitos', '202cb962ac59075b964b07152d234b70', 2, 1),
(14, 'gdjf', 'dhghf@grfeagw', 'ghfd', '202cb962ac59075b964b07152d234b70', 2, 1),
(15, 'efwefwe', 'efwe@fcefws', 'ewfwef', '202cb962ac59075b964b07152d234b70', 2, 1),
(16, 'thrh', 'htr@dwesa', 'htr', '202cb962ac59075b964b07152d234b70', 2, 1),
(17, 'wegrgare', 'greg@af', 'grger', '6ce22b387783a421a2d13d5dce477b48', 2, 1),
(18, 'few', 'fews@dqaw', 'ewf', '0e22f493c7480c3d7e38919ed8a72f6b', 2, 1),
(19, 'fewfe', 'snowraceryt22@gmail.com', 'eee', '202cb962ac59075b964b07152d234b70', 1, 0),
(20, 'wdqwfwe', 'eee444@gmail.com', 'eeee', '202cb962ac59075b964b07152d234b70', 3, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `no_factura` (`no_factura`,`cod_producto`),
  ADD KEY `df_producto` (`cod_producto`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `dt_factura` (`no_factura`),
  ADD KEY `dt_producto` (`cod_producto`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `entreda_producto` (`cod_producto`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`no_factura`),
  ADD KEY `factura_usuaraio` (`usuario`),
  ADD KEY `factura_cliente` (`cod_cliente`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`cod_producto`),
  ADD KEY `producto_proveedor` (`proveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`cod_proveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `usuario_rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `correlativo` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `no_factura` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `cod_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `cod_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `df_factura` FOREIGN KEY (`no_factura`) REFERENCES `factura` (`no_factura`),
  ADD CONSTRAINT `df_producto` FOREIGN KEY (`cod_producto`) REFERENCES `producto` (`cod_producto`);

--
-- Filtros para la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD CONSTRAINT `dt_factura` FOREIGN KEY (`no_factura`) REFERENCES `factura` (`no_factura`),
  ADD CONSTRAINT `dt_producto` FOREIGN KEY (`cod_producto`) REFERENCES `producto` (`cod_producto`);

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entreda_producto` FOREIGN KEY (`cod_producto`) REFERENCES `producto` (`cod_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_cliente` FOREIGN KEY (`cod_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `factura_usuaraio` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `producto_proveedor` FOREIGN KEY (`proveedor`) REFERENCES `proveedor` (`cod_proveedor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_rol` FOREIGN KEY (`rol`) REFERENCES `rol` (`id_rol`);
COMMIT;
