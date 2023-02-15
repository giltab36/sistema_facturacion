<?php

session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}

include "../../conexion.php";
require_once '../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

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

		ob_start();
		include(dirname('__FILE__') . './factura/factura.php');
		$html = ob_get_clean();

		$dompdf = new Dompdf();

		/* $options = $dompdf->getOptions();
		$options->set(array('isRemoteEnabled' => true));
		$dompdf->setOptions($options); */

		//	Carga del HTML
		$dompdf->loadHtml($html);
		$dompdf->setPaper('letter', 'portrait');
		$dompdf->render();
		$dompdf->stream('factura_' . $noFactura . '.pdf', array('Attachment' => false));
		exit;
	}
}
