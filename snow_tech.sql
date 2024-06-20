-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-10-2023 a las 04:15:52
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
		DECLARE nuevo_total decimal (10, 2);
		DECLARE nuevo_precio decimal (10, 2);
        
		DECLARE cant_actual int;
		DECLARE pre_actual decimal (10, 2);
        
		DECLARE actual_existencia int;
		DECLARE actual_precio decimal(10,2);
        
        SELECT precio,existencia INTO actual_precio,actual_existencia FROM producto WHERE cod_producto = codigo;
		SET nueva_existencia = actual_existencia + n_cantidad;
		SET nuevo_total = (actual_existencia * actual_precio) + (n_cantidad * n_precio);
		SET nuevo_precio = nuevo_total / nueva_existencia;
        
		UPDATE producto SET existencia = nueva_existencia, precio = nuevo_precio WHERE cod_producto = codigo;
        
		SELECT nueva_existencia, nuevo_precio;
        
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp` (`codigo` INT, `cantidad` INT, `token_user` VARCHAR(50))   BEGIN

		DECLARE precio_actual decimal(10,2);
		SELECT precio INTO precio_actual FROM producto WHERE cod_producto = codigo;

		INSERT INTO detalle_temp(token_user, cod_producto, cantidad, precio_venta) VALUE(token_user, codigo, cantidad, precio_actual);

		SELECT tmp.correlativo, tmp.cod_producto, p.descripcion, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp
		INNER JOIN producto p
		ON tmp.cod_producto = p.cod_producto
		WHERE tmp.token_user = token_user;

	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `anular_factura` (`nofactura` INT)   BEGIN

		DECLARE existe_factura int;
		DECLARE registros int;
		DECLARE a int;

		DECLARE cod_producto int;
		DECLARE cant_producto int;
		DECLARE existencia_actual int;
		DECLARE nueva_existencia int;

		SET existe_factura = (SELECT COUNT(*) FROM factura WHERE no_factura = nofactura AND estatus = 1);

		IF existe_factura > 0 THEN 
			CREATE TEMPORARY TABLE tbl_tmp (
				id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				cod_prod BIGINT,
				cant_prod int);

				SET a = 1;

				SET registros = (SELECT COUNT(*) FROM detalle_factura WHERE no_factura = nofactura);

				IF registros > 0 THEN
					INSERT INTO tbl_tmp(cod_prod, cant_prod) SELECT cod_producto, cantidad FROM detalle_factura WHERE no_factura = nofactura;

					WHILE a <= registros DO
						SELECT cod_prod, cant_prod INTO cod_producto, cant_producto FROM tbl_tmp WHERE id = a;
						SELECT existencia INTO existencia_actual FROM producto WHERE cod_producto = cod_producto;
						SET nueva_existencia = existencia_actual + cant_producto;
						UPDATE producto SET existencia = nueva_existencia WHERE cod_producto = cod_producto;

						SET a = a+1;
					END WHILE;
					
					UPDATE factura SET estatus = 2 WHERE no_factura = nofactura;
					DROP TABLE tbl_tmp;
					SELECT * FROM factura WHERE no_factura = nofactura;

				END IF;
		ELSE
			SELECT 0 factura;
		END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `data_dashboard` ()   BEGIN

		DECLARE usuarios int;
		DECLARE clientes int;
		DECLARE proveedores int;
		DECLARE productos int;
		DECLARE ventas int;

		SELECT COUNT(*) INTO usuarios FROM usuario WHERE estatus !=10;
		SELECT COUNT(*) INTO clientes FROM cliente WHERE estatus !=10;
		SELECT COUNT(*) INTO proveedores FROM proveedor WHERE estatus !=10;
		SELECT COUNT(*) INTO productos FROM producto WHERE estatus !=10;
		SELECT COUNT(*) INTO ventas FROM factura WHERE fecha > CURDATE() AND estatus !=10;

		SELECT usuarios, clientes, proveedores, productos, ventas;

	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp` (`id_detalle` INT, `token` VARCHAR(50))   BEGIN
		DELETE FROM detalle_temp WHERE correlativo = id_detalle;

		SELECT tmp.correlativo, tmp.cod_producto, p.descripcion, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp
		INNER JOIN producto p
		ON tmp.cod_producto = p.cod_producto
		WHERE tmp.token_user = token;

	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (`cod_usuario` INT, `cod_cliente` INT, `token` VARCHAR(50))   BEGIN
		DECLARE factura int;

		DECLARE registros int;
		DECLARE total decimal(10,2);

		DECLARE nueva_existencia int;
		DECLARE existencia_actual int;

		DECLARE tmp_cod_producto int;
		DECLARE tmp_cant_producto int;
		DECLARE a int;
		SET a = 1;

		CREATE TEMPORARY TABLE tbl_tmp_tokenuser (
			id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			cod_prod BIGINT,
			cant_prod int
		);

		SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);

		IF registros > 0 THEN
			INSERT INTO tbl_tmp_tokenuser(cod_prod, cant_prod) SELECT cod_producto, cantidad FROM detalle_temp WHERE token_user = token;

			INSERT INTO factura(usuario, cod_cliente) VALUE (cod_usuario, cod_cliente);
			SET factura = LAST_INSERT_ID();

			INSERT INTO detalle_factura(no_factura, cod_producto, cantidad, precio_venta) SELECT (factura) AS no_factura, cod_producto, cantidad, precio_venta FROM detalle_temp WHERE token_user = token;

			WHILE a <= registros DO
				SELECT cod_prod, cant_prod INTO tmp_cod_producto, tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
				SELECT existencia INTO existencia_actual FROM producto WHERE cod_producto = tmp_cod_producto;

				SET nueva_existencia = existencia_actual - tmp_cant_producto;
				UPDATE producto SET existencia = nueva_existencia WHERE cod_producto = tmp_cod_producto;

				SET a = a + 1;

			END WHILE;

			SET total = (SELECT SUM(cantidad * precio_venta) FROM detalle_temp WHERE token_user = token);
			UPDATE factura SET total_factura = total WHERE no_factura = factura;
			DELETE FROM detalle_temp WHERE token_user = token;
			TRUNCATE TABLE tbl_tmp_tokenuser;
			SELECT * FROM factura WHERE no_factura = factura;

		ELSE

			SELECT 0;

		END IF;

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
(1, 0, 'C/F', '', '', '2022-08-31 14:03:19', 1, 1),
(2, 5444854, 'Edgar Baez', '485444485', 'San José Obrero', '2022-09-01 10:47:21', 1, 1),
(3, 45456456, 'Hector Villalba', '09416486', 'B° Sirena', '2022-09-17 09:26:41', 1, 1),
(4, 4845645, 'David Aranda', '096345866', 'B° Maria Graciela', '2022-09-17 09:27:21', 1, 1),
(5, 122222, 'Carlos', '0972406538', 'San Ignacio', '2022-10-14 18:34:11', 1, 0),
(6, 4564456, 'gxhjfc', '05641864', 'Mburukuja', '2023-01-23 14:17:57', 1, 1),
(7, 7541962, 'Luz Villar', '0975216384', 'Encarnacion', '2023-01-26 22:41:32', 1, 1),
(10, 2216503, 'Claro Villar', '0972728742', 'Ayolas - B° San José Obrero', '2023-02-03 18:09:47', 1, 1),
(11, 0, 'Carlos Vasquez', '0972 728 742', 'Asuncion', '2023-02-03 18:19:02', 1, 1),
(15, 4162979, 'Liz Rossana Medina', '0972162163', 'San Jose Obrero', '2023-02-15 20:53:04', 1, 1),
(16, 5510507, 'Luis Herrera', '44456456', 'Ayolas - Nucleo 2', '2023-02-16 17:07:01', 1, 1),
(17, 0, 'Pepe', '1658918948', 'Ayolas - Nucleo 2', '2023-02-17 19:21:57', 1, 1),
(18, 754, 'Pablo', '158188', 'Ayolas', '2023-02-17 19:24:38', 1, 1);

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
(1, '6571863-4', 'Tech S.A.', 'Venta y Más', '0972406538', 'info.tech@gmail.com', 'Ayolas, Paraguay', '12.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `correlativo` bigint(11) NOT NULL,
  `no_factura` bigint(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_factura`
--

INSERT INTO `detalle_factura` (`correlativo`, `no_factura`, `cod_producto`, `cantidad`, `precio_venta`) VALUES
(1, 1, 1, 1, '82000.00'),
(2, 1, 7, 1, '58000.00'),
(4, 2, 1, 1, '82000.00'),
(5, 2, 7, 1, '58000.00'),
(7, 3, 1, 1, '82000.00'),
(8, 3, 7, 1, '58000.00'),
(10, 5, 1, 1, '82000.00'),
(11, 5, 7, 1, '58000.00'),
(13, 6, 13, 1, '62904.76'),
(14, 6, 1, 3, '82000.00'),
(16, 7, 1, 3, '82000.00'),
(17, 8, 5, 1, '58000.00'),
(18, 9, 9, 1, '6129844.67'),
(19, 10, 9, 1, '6129844.67'),
(20, 11, 7, 1, '58000.00'),
(21, 12, 1, 1, '82000.00'),
(22, 12, 7, 1, '58000.00'),
(23, 12, 6, 1, '150000.00'),
(24, 12, 5, 1, '58000.00'),
(28, 13, 5, 1, '58000.00'),
(29, 13, 10, 1, '87142.86'),
(30, 13, 12, 3, '58000.00'),
(31, 13, 13, 1, '62904.76'),
(35, 14, 13, 3, '62904.76'),
(36, 14, 9, 1, '6129844.67'),
(37, 14, 2, 2, '769230.77'),
(38, 15, 1, 1, '82000.00'),
(39, 15, 5, 1, '58000.00'),
(40, 16, 9, 1, '6129844.67'),
(41, 17, 7, 1, '58000.00'),
(42, 18, 7, 1, '58000.00'),
(43, 19, 7, 1, '58000.00'),
(44, 20, 9, 1, '6129844.67'),
(45, 21, 7, 1, '58000.00'),
(46, 22, 1, 1, '82000.00'),
(47, 23, 6, 1, '150000.00'),
(48, 24, 9, 1, '6129844.67'),
(49, 25, 9, 1, '6129844.67'),
(50, 26, 13, 1, '62904.76'),
(51, 27, 2, 1, '769230.77'),
(52, 28, 9, 1, '6129844.67'),
(53, 29, 1, 1, '82000.00'),
(54, 30, 9, 1, '6129844.67'),
(55, 30, 11, 1, '20400.00'),
(56, 30, 2, 1, '769230.77'),
(57, 30, 12, 1, '58000.00'),
(61, 31, 9, 1, '6129844.67'),
(62, 31, 11, 1, '20400.00'),
(63, 31, 13, 3, '62904.76'),
(64, 31, 1, 1, '82000.00'),
(68, 32, 9, 1, '6129844.67'),
(69, 32, 7, 1, '58000.00'),
(70, 32, 9, 1, '6129844.67'),
(71, 33, 1, 1, '82000.00'),
(72, 34, 1, 1, '82000.00'),
(73, 35, 1, 3, '82000.00'),
(74, 36, 1, 2, '82000.00'),
(75, 36, 2, 2, '769230.77'),
(77, 37, 9, 1, '6129844.67'),
(78, 38, 7, 1, '58000.00'),
(79, 39, 7, 1, '58000.00'),
(80, 40, 1, 1, '82000.00'),
(81, 41, 1, 1, '82000.00'),
(82, 41, 7, 1, '58000.00'),
(83, 41, 10, 1, '87142.86'),
(84, 42, 1, 1, '82000.00'),
(85, 42, 10, 1, '87142.86'),
(87, 43, 1, 1, '82000.00'),
(88, 43, 7, 1, '58000.00'),
(89, 44, 1, 1, '82000.00'),
(90, 45, 1, 1, '82000.00');

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

--
-- Volcado de datos para la tabla `detalle_temp`
--

INSERT INTO `detalle_temp` (`correlativo`, `token_user`, `cod_producto`, `cantidad`, `precio_venta`) VALUES
(100, '3c59dc048e8850243be8079a5c74d079', 1, 1, '82000.00'),
(103, 'c4ca4238a0b923820dcc509a6f75849b', 1, 1, '82000.00');

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
(25, 14, '2023-01-25 22:14:51', 50, '125000.00', 1),
(26, 15, '2023-02-16 17:11:21', 50, '80000.00', 1),
(27, 16, '2023-02-16 17:16:31', 25, '35000.00', 1),
(28, 17, '2023-02-16 17:17:09', 25, '35000.00', 1),
(29, 18, '2023-02-16 17:18:43', 35, '60000.00', 1),
(30, 19, '2023-02-17 19:23:22', 36, '20000.00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `no_factura` bigint(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` int(11) NOT NULL,
  `cod_cliente` int(11) NOT NULL,
  `total_factura` decimal(10,2) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`no_factura`, `fecha`, `usuario`, `cod_cliente`, `total_factura`, `estatus`) VALUES
(1, '2023-02-06 23:27:00', 1, 2, '0.00', 2),
(2, '2023-02-06 23:30:50', 1, 2, '0.00', 2),
(3, '2023-02-06 23:33:38', 1, 2, '0.00', 1),
(5, '2023-02-06 23:34:07', 1, 2, '140000.00', 2),
(6, '2023-02-07 00:49:41', 1, 10, '308904.76', 1),
(7, '2023-02-07 00:53:33', 1, 1, '246000.00', 1),
(8, '2023-02-07 14:09:13', 1, 1, '58000.00', 1),
(9, '2023-02-07 14:10:33', 1, 1, '6129844.67', 1),
(10, '2023-02-07 14:11:51', 1, 1, '6129844.67', 1),
(11, '2023-02-07 14:13:32', 1, 1, '58000.00', 1),
(12, '2023-02-07 14:44:17', 1, 3, '348000.00', 1),
(13, '2023-02-07 14:47:06', 1, 4, '382047.62', 2),
(14, '2023-02-07 14:57:07', 1, 7, '7857020.49', 2),
(15, '2023-02-08 21:59:41', 1, 3, '140000.00', 1),
(16, '2023-02-11 11:13:36', 1, 1, '6129844.67', 2),
(17, '2023-02-12 23:40:13', 1, 1, '0.00', 1),
(18, '2023-02-12 23:40:23', 1, 1, '0.00', 1),
(19, '2023-02-12 23:41:31', 1, 1, '58000.00', 1),
(20, '2023-02-13 00:02:15', 1, 1, '6129844.67', 1),
(21, '2023-02-13 00:03:10', 1, 1, '58000.00', 1),
(22, '2023-02-13 00:07:24', 1, 1, '82000.00', 1),
(23, '2023-02-13 00:08:03', 1, 1, '150000.00', 1),
(24, '2023-02-13 00:09:01', 1, 1, '6129844.67', 1),
(25, '2023-02-13 00:10:30', 1, 1, '6129844.67', 1),
(26, '2023-02-13 00:10:47', 1, 1, '62904.76', 2),
(27, '2023-02-13 00:30:13', 1, 1, '769230.77', 1),
(28, '2023-02-13 00:57:53', 1, 1, '6129844.67', 1),
(29, '2023-02-13 01:04:41', 1, 1, '82000.00', 2),
(30, '2023-02-13 01:08:06', 1, 10, '6977475.44', 1),
(31, '2023-02-13 01:09:59', 1, 7, '6420958.95', 2),
(32, '2023-02-13 01:11:59', 1, 4, '12317689.34', 2),
(33, '2023-02-13 01:13:33', 1, 1, '82000.00', 2),
(34, '2023-02-13 01:22:33', 1, 1, '82000.00', 2),
(35, '2023-02-13 02:14:38', 1, 7, '246000.00', 2),
(36, '2023-02-13 12:07:32', 1, 7, '1702461.54', 2),
(37, '2023-02-13 13:45:35', 1, 10, '6129844.67', 1),
(38, '2023-02-15 00:53:43', 1, 1, '58000.00', 1),
(39, '2023-02-15 00:55:16', 1, 1, '58000.00', 1),
(40, '2023-02-15 00:56:05', 1, 7, '82000.00', 1),
(41, '2023-02-15 20:53:59', 1, 15, '227142.86', 2),
(42, '2023-02-16 17:08:41', 1, 16, '169142.86', 1),
(43, '2023-02-16 17:42:48', 1, 4, '140000.00', 2),
(44, '2023-02-17 19:25:10', 1, 18, '82000.00', 2),
(45, '2023-06-26 21:02:59', 1, 1, '82000.00', 2);

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
(1, '260489098', 'Mouse USB', 1, '82000.00', 222, '2022-09-16 18:27:04', 1, 1, 'img_producto.png'),
(2, '165841981', 'Monitor LCD', 1, '769230.77', 124, '2022-09-16 18:29:53', 2, 1, 'img_producto.png'),
(3, '496848757', '', 4, '0.00', 0, '2022-10-13 18:44:47', 1, 0, 'img_producto.png'),
(4, '168486168', '', 4, '0.00', 0, '2022-10-25 23:05:17', 1, 0, 'img_producto.png'),
(5, '778646486', 'Pantalla LG', 4, '58000.00', 196, '2022-10-25 23:06:50', 1, 1, 'img_producto.png'),
(6, '168946816', 'teclado', 3, '150000.00', 48, '2022-10-25 23:08:26', 1, 1, 'img_producto.png'),
(7, '235856458', 'Pantalla LG', 1, '58000.00', 40, '2022-10-27 10:00:56', 1, 1, 'img_producto.png'),
(8, '468749841', 'Play Station 5', 1, '58000.00', 50, '2022-10-27 10:42:27', 1, 0, 'img_5fcee1930f42e1b33b1c1bee12ac2508.jpg'),
(9, '221683885', 'Play Station 5', 1, '6129844.67', 137, '2022-10-27 10:48:55', 1, 1, 'img_903a6b2ce0622003767f03856e2c7743.jpg'),
(10, '184968258', 'USB Sandisk 64Gb', 3, '87142.86', 67, '2022-10-27 10:56:48', 1, 1, 'img_6081c4412471f54e32aea0057b783e62.jpg'),
(11, '351165686', 'Mousepad', 1, '20400.00', 48, '2022-10-27 11:02:02', 1, 1, 'img_producto.png'),
(12, '654648684', 'HDDR', 3, '58000.00', 452, '2022-10-27 12:29:21', 1, 1, 'img_producto.png'),
(13, '165468648', 'Teclado', 4, '62904.76', 201, '2022-10-27 12:33:23', 1, 1, 'img_c15ffdc2b64472d4321305fc6d336487.jpg'),
(14, '528496165', 'Vaso Termico', 5, '137500.00', 100, '2023-01-25 22:11:16', 1, 1, 'img_c5edad2edd5e8a9615d91245e91d4138.jpg'),
(15, '', 'Cargador Portatil', 4, '80000.00', 50, '2023-02-16 17:11:21', 1, 1, 'img_producto.png'),
(16, '', 'Reloj Casio', 3, '35000.00', 25, '2023-02-16 17:16:31', 1, 1, 'img_producto.png'),
(17, '', 'Reloj Casio', 3, '35000.00', 25, '2023-02-16 17:17:09', 1, 0, 'img_producto.png'),
(18, '', 'Mousepad', 5, '60000.00', 35, '2023-02-16 17:18:43', 1, 1, 'img_producto.png'),
(19, '', 'Casco', 6, '20000.00', 36, '2023-02-17 19:23:22', 1, 1, 'img_producto.png');

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
(1, 'Big Center - Asuncion', 'Noelia Escobar', '0975515418', 'Asuncion', '2022-09-13 00:14:39', 1, 0),
(2, 'PC House', 'Maria', '0985446458', 'B° San Antonio', '2022-09-17 08:41:51', 1, 1),
(3, 'Informática 1000', 'Patricia Mir', '0945485254', 'Mil Viviendas', '2022-09-17 08:49:28', 1, 1),
(4, 'Kingston', 'Marta Sanabria', '0974841485', 'Asuncion', '2022-09-17 09:30:40', 1, 1),
(5, 'Big Center - Ayolas', 'Marcela', '8787878', 'Ayolas', '2022-10-14 18:38:32', 1, 1),
(6, 'Camerun ', 'Carmen', '5949', 'Asuncion', '2023-02-17 19:22:35', 1, 1);

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
(1, 'Jose Villar', 'josema95035@gmail.com', 'admin', 'c1fea270c48e8079d8ddf7d06d26ab52', 1, 1),
(2, 'Micaela Bustamante', 'micabusta@gmail.com', 'mica', '202cb962ac59075b964b07152d234b70', 3, 1),
(3, 'Guillermo Cordobe', 'memoarg@gmail.com', 'Memo', '202cb962ac59075b964b07152d234b70', 2, 1),
(21, 'Carlos Vasquez', 'carlos74vasquez@gmail.com', 'carlitos16', 'e10adc3949ba59abbe56e057f20f883e', 3, 1),
(22, 'Laura', 'lau@gmail.com', 'lau5', '81dc9bdb52d04dc20036dbd8313ed055', 3, 1);

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
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `correlativo` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `no_factura` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `cod_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `cod_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
