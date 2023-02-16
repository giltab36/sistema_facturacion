<?php
session_start();

$subtotal     = 0;
$iva          = 0;
$impuesto     = 0;
$tl_sniva   = 0;
$total         = 0;

include "../../conexion.php";

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
            $anulada = '""""""""""  Anulada  """""""""""';
        }

        $query_productos = mysqli_query($conection, "SELECT p.descripcion,dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total
														FROM factura f
														INNER JOIN detalle_factura dt ON f.no_factura = dt.no_factura
														INNER JOIN producto p ON dt.cod_producto = p.cod_producto
														WHERE f.no_factura = $no_factura ");
        $result_detalle = mysqli_num_rows($query_productos);
    }
}

# Incluyendo librerias necesarias #
require "./code128.php";

$pdf = new PDF_Code128('P', 'mm', array(80, 258));
$pdf->SetMargins(4, 10, 4);
$pdf->AddPage();


# Encabezado y datos de la empresa #
if ($result_config > 0) {
    $iva = $configuracion['iva'];
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 5, utf8_decode(strtoupper($configuracion['nombre'])), 0, 'C', false);
    $pdf->SetFont('Arial', '', 9);
    $pdf->MultiCell(0, 5, utf8_decode("RUC: " . $configuracion['ruc']), 0, 'C', false);
    $pdf->MultiCell(0, 5, utf8_decode($configuracion['direccion']), 0, 'C', false);
    $pdf->MultiCell(0, 5, utf8_decode("Teléfono: " . $configuracion['telefono']), 0, 'C', false);
    $pdf->MultiCell(0, 5, utf8_decode("Email: " . $configuracion['email']), 0, 'C', false);
}

$pdf->Ln(1);
$pdf->Cell(0, 5, utf8_decode("------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(5);

$pdf->MultiCell(0, 5, utf8_decode("Fecha: " . $factura['fecha']), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Hora: " . $factura['hora']), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Caja Nro: 1"), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Cajero: " . $factura['vendedor']), 0, 'C', false);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(0, 5, utf8_decode(strtoupper("Ticket Nro: " . $factura['no_factura'])), 0, 'C', false);
$pdf->SetFont('Arial', '', 9);

$pdf->Ln(1);
$pdf->Cell(0, 5, utf8_decode("------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(5);

$pdf->MultiCell(0, 5, utf8_decode("Cliente: " . $factura['nombre']), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Documento N°: " . $factura['cedula']), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Teléfono: " . $factura['telefono']), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Dirección: " . $factura['direccion']), 0, 'C', false);

$pdf->Ln(1);
$pdf->Cell(0, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(3);


# Tabla de productos #
$pdf->Cell(23, 5, utf8_decode("Cant."), 0, 0, 'C');
$pdf->Cell(19, 5, utf8_decode("Precio"), 0, 0, 'C');
/* $pdf->Cell(18, 5, utf8_decode("Dec."), 0, 0, 'C'); */
$pdf->Cell(28, 5, utf8_decode("Total"), 0, 0, 'C');

$pdf->Ln(3);
$pdf->Cell(72, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(3);


/*----------  Detalles de la tabla  ----------*/
/* if ($factura['estatus'] != 2) { */

if ($result_detalle > 0) {
    while ($row = mysqli_fetch_assoc($query_productos)) {
        $pdf->MultiCell(0, 4, utf8_decode($row['descripcion']), 0, 'C', false);
        $pdf->Cell(22, 4, utf8_decode($row['cantidad']), 0, 0, 'C');
        $pdf->Cell(19, 4, utf8_decode("G. " . $row['precio_venta']), 0, 0, 'C');
        /* $pdf->Cell(19, 4, utf8_decode("$0.00 USD"), 0, 0, 'C'); */
        $pdf->Cell(28, 4, utf8_decode("G. " . $row['precio_total']), 0, 0, 'C');
        $pdf->Ln(4);
        /* $pdf->MultiCell(0, 4, utf8_decode("Garantía de fábrica: 2 Meses"), 0, 'C', false); */
        $pdf->Ln(2);
        $precio_total = $row['precio_total'];
        $subtotal = round($subtotal + $precio_total, 2);
    }
}
/* }else{
    $pdf->Ln(4);
    $pdf->MultiCell(0, 4, utf8_decode($anulada), 0, 'C', false);
} */
/*----------  Fin Detalles de la tabla  ----------*/


$pdf->Cell(72, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');

$pdf->Ln(5);

# Impuestos & totales #
$impuesto     = round($subtotal * ($iva / 100), 2);
$tl_sniva     = round($subtotal - $impuesto, 2);
$total         = round($tl_sniva + $impuesto, 2);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("SUBTOTAL"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("G. " . $tl_sniva), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("IVA (" . $iva . ")%"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("G. " . $impuesto), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(72, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("TOTAL A PAGAR"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("G. " . $total), 0, 0, 'C');

$pdf->Ln(5);

/* $pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("TOTAL PAGADO"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("$100.00 USD"), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("CAMBIO"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("$30.00 USD"), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("USTED AHORRA"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("$0.00 USD"), 0, 0, 'C'); */

$pdf->Ln(10);

$pdf->MultiCell(0, 5, utf8_decode("*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar este ticket ***"), 0, 'C', false);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 7, utf8_decode("Gracias por su compra"), '', 0, 'C');

$pdf->Ln(9);

# Codigo de barras #
$pdf->Code128(5, $pdf->GetY(), $noFactura, 70, 20);
$pdf->SetXY(0, $pdf->GetY() + 21);
$pdf->SetFont('Arial', '', 14);
/* $pdf->MultiCell(0, 5, utf8_decode(strtoupper(md5($noFactura))), 0, 'C', false); */

# Nombre del archivo PDF #
$pdf->Output("I", "Ticket_Nro_" . $noFactura . ".pdf", true);
