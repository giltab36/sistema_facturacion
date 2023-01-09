-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-01-2023 a las 18:32:36
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
(3, '', 4, '0.00', 0, '2022-10-13 18:44:47', 1, 0, 'img_producto.png'),
(4, '', 4, '0.00', 0, '2022-10-25 23:05:17', 1, 0, 'img_producto.png'),
(5, 'Pantalla LG', 4, '58000.00', 200, '2022-10-25 23:06:50', 1, 1, 'img_producto.png'),
(6, 'teclado', 3, '150000.00', 50, '2022-10-25 23:08:26', 1, 1, 'img_producto.png'),
(7, 'Pantalla LG', 1, '58000.00', 55, '2022-10-27 10:00:56', 1, 1, 'img_producto.png'),
(8, 'Play Station 5', 1, '58000.00', 50, '2022-10-27 10:42:27', 1, 1, 'img_133dfd2a3797bc539339df744aff5caejpg'),
(9, 'Play Station 5', 1, '58000.00', 50, '2022-10-27 10:48:55', 1, 1, 'img_903a6b2ce0622003767f03856e2c7743.jpg'),
(10, 'Pendrive Sandisk 64Gb', 1, '84000.00', 20, '2022-10-27 10:56:48', 1, 1, 'img_a3317cae44ff614dada03fedf5d76db3.jpg'),
(11, 'Mousepad', 1, '15000.00', 10, '2022-10-27 11:02:02', 1, 1, 'img_producto.png'),
(12, 'hdrd', 3, '58000.00', 456, '2022-10-27 12:29:21', 1, 1, 'img_producto.png'),
(13, 'teclado', 4, '63066.67', 150, '2022-10-27 12:33:23', 1, 1, 'img_c15ffdc2b64472d4321305fc6d336487.jpg');

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

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`cod_producto`),
  ADD KEY `producto_proveedor` (`proveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `cod_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `producto_proveedor` FOREIGN KEY (`proveedor`) REFERENCES `proveedor` (`cod_proveedor`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;
