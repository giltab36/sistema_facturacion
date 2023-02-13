<?php
$subtotal 	= 0;
$iva 	 	= 0;
$impuesto 	= 0;
$tl_sniva   = 0;
$total 		= 0;
//print_r($configuracion); 

include "../../conexion.php";
require_once '../pdf/vendor/autoload.php';

if (empty($_REQUEST['cl']) || empty($_REQUEST['f'])) {
	echo "No es posible generar la factura.";
} else {
	$codCliente = $_REQUEST['cl'];
	$noFactura = $_REQUEST['f'];
	$anulada = '';

	$query_config   = mysqli_query($conection, "SELECT * FROM configuracion");
	$result_config  = mysqli_num_rows($query_config);
	if ($result_config > 0) {
		$configuracion = mysqli_fetch_assoc($query_config);
	}


	$query = mysqli_query($conection, "SELECT f.no_factura, DATE_FORMAT(f.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.fecha,'%H:%i:%s') as  hora, f.cod_cliente, f.estatus,
												 v.nombre as vendedor,
												 cl.cedula, cl.nombre, cl.telefono,cl.direccion
											FROM factura f
											INNER JOIN usuario v ON f.usuario = v.id_usuario
											INNER JOIN cliente cl ON f.cod_cliente = cl.id_cliente
											WHERE f.no_factura = $noFactura AND f.cod_cliente = $codCliente  AND f.estatus != 10 ");

	$result = mysqli_num_rows($query);
	if ($result > 0) {

		$factura = mysqli_fetch_assoc($query);
		$no_factura = $factura['no_factura'];

		if ($factura['estatus'] == 2) {
			$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
		}

		$query_productos = mysqli_query($conection, "SELECT p.descripcion,dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total
														FROM factura f
														INNER JOIN detalle_factura dt ON f.no_factura = dt.no_factura
														INNER JOIN producto p ON dt.cod_producto = p.cod_producto
														WHERE f.no_factura = $no_factura ");
		$result_detalle = mysqli_num_rows($query_productos);
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Factura</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php echo $anulada; ?>
	<div id="page_pdf">
		<table id="factura_head">
			<tr>
				<td class="logo_factura">
					<div>
						<img src="img/logo.png">
					</div>
				</td>
				<td class="info_empresa">
					<?php
					if ($result_config > 0) {
						$iva = $configuracion['iva'];
					?>
						<div>
							<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
							<p><?php echo $configuracion['razon_social']; ?></p>
							<p><?php echo $configuracion['direccion']; ?></p>
							<p>Ruc: <?php echo $configuracion['ruc']; ?></p>
							<p>Teléfono: <?php echo $configuracion['telefono']; ?></p>
							<p>Email: <?php echo $configuracion['email']; ?></p>
						</div>
					<?php
					}
					?>
				</td>
				<td class="info_factura">
					<div class="round">
						<span class="h3">Factura</span>
						<p>No. Factura: <strong><?php echo $factura['no_factura']; ?></strong></p>
						<p>Fecha: <?php echo $factura['fecha']; ?></p>
						<p>Hora: <?php echo $factura['hora']; ?></p>
						<p>Vendedor: <?php echo $factura['vendedor']; ?></p>
					</div>
				</td>
			</tr>
		</table>
		<table id="factura_cliente">
			<tr>
				<td class="info_cliente">
					<div class="round">
						<span class="h3">Cliente</span>
						<table class="datos_cliente">
							<tr>
								<?php if (empty($factura['cedula'])) { ?>
									<td><label>Ruc:</label>
										<p> S/C </p>
									</td>
								<?php } else { ?>
									<td><label>Ruc:</label>
										<p><?php echo $factura['cedula']; ?></p>
									</td>
								<?php } ?>

								<?php if (empty($factura['telefono'])) { ?>
									<td><label>Teléfono:</label>
										<p> ------ </p>
									</td>
								<?php } else { ?>
									<td><label>Teléfono:</label>
										<p><?php echo $factura['telefono']; ?></p>
									</td>
								<?php } ?>
							</tr>
							<tr>
								<td><label>Nombre:</label>
									<p><?php echo $factura['nombre']; ?></p>
								</td>

								<?php if (empty($factura['telefono'])) { ?>
									<td><label>Dirección:</label>
										<p> ------ </p>
									</td>
								<?php } else { ?>
									<td><label>Dirección:</label>
										<p><?php echo $factura['direccion']; ?></p>
									</td>
								<?php } ?>
							</tr>
						</table>
					</div>
				</td>

			</tr>
		</table>

		<table id="factura_detalle">
			<thead>
				<tr>
					<th width="50px">Cant.</th>
					<th class="textleft">Descripción</th>
					<th class="textright" width="150px">Precio Unitario.</th>
					<th class="textright" width="150px"> Precio Total</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">

				<?php

				if ($result_detalle > 0) {

					while ($row = mysqli_fetch_assoc($query_productos)) {
				?>
						<tr>
							<td class="textcenter"><?php echo $row['cantidad']; ?></td>
							<td><?php echo $row['descripcion']; ?></td>
							<td class="textright"><?php echo $row['precio_venta']; ?></td>
							<td class="textright"><?php echo $row['precio_total']; ?></td>
						</tr>
				<?php
						$precio_total = $row['precio_total'];
						$subtotal = round($subtotal + $precio_total, 2);
					}
				}

				$impuesto 	= round($subtotal * ($iva / 100), 2);
				$tl_sniva 	= round($subtotal - $impuesto, 2);
				$total 		= round($tl_sniva + $impuesto, 2);
				?>
			</tbody>
			<tfoot id="detalle_totales">
				<tr>
					<td colspan="3" class="textright"><span>SUBTOTAL Q.</span></td>
					<td class="textright"><span><?php echo $tl_sniva; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>IVA (<?php echo $iva; ?> %)</span></td>
					<td class="textright"><span><?php echo $impuesto; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>TOTAL Q.</span></td>
					<td class="textright"><span><?php echo $total; ?></span></td>
				</tr>
			</tfoot>
		</table>
		<div>
			<p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con nombre, teléfono y Email</p>
			<h4 class="label_gracias">¡Gracias por su compra!</h4>
		</div>

	</div>

</body>

</html>