-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-01-2023 a las 05:16:44
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `tienda`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_precio_producto` (`n_cantidad` INT, `n_precio` DECIMAL(10,2), `codigo` INT)   BEGIN
    	DECLARE nueva_existencia int;
        DECLARE nuevo_total decimal(10,2);
        DECLARE nuevo_precio decimal(10,2);
        
        DECLARE cant_actual int;
        DECLARE pre_actual decimal(10,2);
        
        DECLARE actual_existencia int;
        DECLARE actual_precio decimal(10,2);
        
        SELECT precio, existencia INTO actual_precio, actual_existencia FROM producto WHERE cod_producto = codigo;
        SET nueva_existencia = actual_existencia + n_cantidad;
        SET nuevo_total = (actual_existencia * actual_precio) + (n_cantidad * n_precio);
        SET nuevo_precio = nuevo_total / nueva_existencia;
        
        UPDATE producto SET existencia = nueva_existencia, precio = nuevo_precio WHERE cod_producto = codigo;
        
        SELECT nueva_existencia, nuevo_precio;
        
    END$$

DELIMITER ;

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
(5, 122222, 'Carlos', '0972406538', 'San Ignacio', '2022-10-14 18:34:11', 1, 0),
(6, 4564456, 'gxhjfc', '05641864', 'Mburukuja', '2023-01-23 14:17:57', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` bigint(20) NOT NULL,
  `ruc` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `direccion` text NOT NULL,
  `iva` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `ruc`, `nombre`, `razon_social`, `telefono`, `email`, `direccion`, `iva`) VALUES
(1, '4564565-4', 'Tech Reformation', '', '0972406538', 'info.tech@gmail.com', 'Ayolas, Paraguay', '10.00');

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
  `token_user` varchar(50) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL
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
(13, 13, '2022-10-27 12:33:23', 130, '58000.00', 1),
(14, 11, '2023-01-17 16:22:06', 10, '20000.00', 1),
(15, 11, '2023-01-17 16:23:41', 10, '20000.00', 1),
(16, 11, '2023-01-17 16:24:22', 20, '25000.00', 1),
(17, 10, '2023-01-17 16:30:02', 50, '90000.00', 1),
(18, 9, '2023-01-17 16:58:22', 100, '6000000.00', 1),
(19, 13, '2023-01-17 17:00:05', 50, '70000.00', 1),
(20, 13, '2023-01-17 17:07:53', 10, '25000.00', 1),
(21, 11, '2023-01-17 17:14:42', 10, '17000.00', 1),
(22, 2, '2023-01-17 17:46:16', 25, '157000.00', 1),
(23, 2, '2023-01-17 17:47:47', 5, '480000.00', 1),
(24, 14, '2023-01-25 22:11:16', 50, '150000.00', 1),
(25, 14, '2023-01-25 22:14:51', 50, '125000.00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `no_factura` bigint(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `usuario` int(11) NOT NULL,
  `cod_cliente` int(11) NOT NULL,
  `total_factura` decimal(10,2) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `cod_producto` int(11) NOT NULL,
  `cod_barra` varchar(100) NOT NULL,
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

INSERT INTO `producto` (`cod_producto`, `cod_barra`, `descripcion`, `proveedor`, `precio`, `existencia`, `date_add`, `usuario_id`, `estatus`, `foto`) VALUES
(1, '260489098', 'Mouse USB', 1, '82000.00', 250, '2022-09-16 18:27:04', 1, 1, 'img_producto.png'),
(2, '165841981', 'Monitor LCD', 1, '769230.77', 130, '2022-09-16 18:29:53', 2, 1, 'img_producto.png'),
(3, '496848757', '', 4, '0.00', 0, '2022-10-13 18:44:47', 1, 0, 'img_producto.png'),
(4, '168486168', '', 4, '0.00', 0, '2022-10-25 23:05:17', 1, 0, 'img_producto.png'),
(5, '778646486', 'Pantalla LG', 4, '58000.00', 200, '2022-10-25 23:06:50', 1, 1, 'img_producto.png'),
(6, '168946816', 'teclado', 3, '150000.00', 50, '2022-10-25 23:08:26', 1, 1, 'img_producto.png'),
(7, '235856458', 'Pantalla LG', 1, '58000.00', 55, '2022-10-27 10:00:56', 1, 1, 'img_producto.png'),
(8, '468749841', 'Play Station 5', 1, '58000.00', 50, '2022-10-27 10:42:27', 1, 0, 'img_5fcee1930f42e1b33b1c1bee12ac2508.jpg'),
(9, '221683885', 'Play Station 5', 1, '6129844.67', 150, '2022-10-27 10:48:55', 1, 1, 'img_903a6b2ce0622003767f03856e2c7743.jpg'),
(10, '184968258', 'USB Sandisk 64Gb', 3, '87142.86', 70, '2022-10-27 10:56:48', 1, 1, 'img_6081c4412471f54e32aea0057b783e62.jpg'),
(11, '351165686', 'Mousepad', 1, '20400.00', 50, '2022-10-27 11:02:02', 1, 1, 'img_producto.png'),
(12, '654648684', 'HDDR', 3, '58000.00', 456, '2022-10-27 12:29:21', 1, 1, 'img_producto.png'),
(13, '165468648', 'Teclado', 4, '62904.76', 210, '2022-10-27 12:33:23', 1, 1, 'img_c15ffdc2b64472d4321305fc6d336487.jpg'),
(14, '528496165', 'Vaso Termico', 5, '137500.00', 100, '2023-01-25 22:11:16', 1, 1, 'img_c5edad2edd5e8a9615d91245e91d4138.jpg');

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
(1, 'Big Center - Asuncion', 'Noelia Escobar', '0975515418', 'Asuncion', '2022-09-13 00:14:39', 1, 1),
(2, 'PC House', 'Maria', '0985446458', 'B° San Antonio', '2022-09-17 08:41:51', 1, 1),
(3, 'Informática 1000', 'Patricia Mir', '0945485254', 'Mil Viviendas', '2022-09-17 08:49:28', 1, 1),
(4, 'Kingston', 'Marta Sanabria', '0974841485', 'Asuncion', '2022-09-17 09:30:40', 1, 1),
(5, 'Big Center - Ayolas', 'Marcela', '8787878', 'Ayolas', '2022-10-14 18:38:32', 1, 1);

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
(2, 'Micaela Bustamante', 'micabusta@gmail.com', 'mica', '202cb962ac59075b964b07152d234b70', 3, 1),
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
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `dt_factura` (`token_user`),
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
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `no_factura` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `cod_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
