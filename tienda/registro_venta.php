<?php

session_start();

include "../conexion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Registro de Ventas</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <div class="title_page">
            <h1 class="title"><i class="fa-solid fa-dollar-sign"></i> Nueva Ventas</h1>
        </div>
        <div class="datos_cliente">
            <div class="action_cliente">
                <h4>Datos del Cliente</h4>
                <a href="#" class="btn_new btn_new_cliente"><i class="fas fa-plus"></i> Nuevo Cliente</a>
            </div>
            <forn name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos">
                <input type="hidden" name="action" value=" addCliente">
                <input type="hidden" id="idcliente" name="idcliente" value="" required>
                <div class="wd30">
                    <label>Numero de Cedula o Ruc.</label>
                    <input type="text" name="ruc_cliente" id="ruc_cliente">
                </div>
                <div class="wd30">
                    <labe1>Nombre</label>
                    <input type="text" name="nom_cliente" id="nom_cliente" disabled required>
                </div>
                <div class="wd30">
                    <label>Teléfono</label>
                    <input type="text" name="tel_cliente" id="tel_cliente" disabled required>
                </div>
                <div class="wd100">
                    <label>Dirección</label>
                    <input type="text" name="dir_ cliente" id="dir_cliente" disabled required>
                </div>
                <div id="div_registro_cliente" class="wd100">
                    <button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Guardar</button>
                </div>
            </forn>
        </div>

        <div class="datos_venta">
            <h4>Datos de Venta</h4>
            <div class="datos ">
                <div class="wd50">
                    <label>Vendedor</label>
                    <p>Carlos Estrada Porras</p>
                </div>
                <div clas s="wd 50">
                    <label>Acciones </label>
                    <div id="acciones_venta">
                        <a href="#" class="btn_cancel textcenter" id="btn_anular_venta"><i class="fas fa-ban"></i> Anular</a>
                        <a href="#" class="btn_new textcenter" id="btn_facturar_venta"><i class="far fa-edit"></i> Procesar</a>
                    </div>
                </div>
            </div>
        </div>

        <table class="tbl_venta">
            <thead>
                <tr>
                    <th width="100px">Codigo</th>
                    <th>Descripcion</th>
                    <th>Existencia</th>
                    <th width="100px">Cantidad</th>
                    <th class="textright">Precio</th>
                    <th class="textright">Precio Total</th>
                    <th>Accion</th>
                </tr>
                <tr>
                    <td><input type="text" name="txt_cod_producto" id="txt_cod_producto"></td>
                    <td id="txt_descripcion">-</td>
                    <td id="txt_existencia">-</td>
                    <td><input type="text" name="txt_cod_producto" id="txt_cod_producto" value="0" min="1" disabled></td>
                    <td id="txt_precio" class="textright">0.00</td>
                    <td id="txt_precio_total" class="textright">0.00</td>
                    <td><a href="#" id="add_product_venta" class="link_add"><i class="fas fa-plus"></i> Agregar</a></td>
                </tr>
                <tr>
                    <th>Codigo</th>
                    <th colspan="2">Descripcion</th>
                    <th>Cantidad</th>
                    <th class="textright">Precio</th>
                    <th class="textright">Precio Total</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody id="detalle_venta">
                <tr>
                    <td>1</td>
                    <td colspan="2">Mouse USB</td>
                    <td class="textcenter">1</td>
                    <td class="textright">100.00</td>
                    <td class="textright">100.00</td>
                    <td><a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle(1);"><i class="far fa-trash-alt"></i></a></td>
                </tr>
            </tbody>
            
        </table>
        <table class="tbl_venta line_off">
        <tfoot>
                <tr>
                    <td colspan="5" class="textright">SUBTOTAL G.</td>
                    <td class="textright">1000.00</td>
                </tr>
                <tr>
                    <td colspan="5" class="textright">IVA (10%)</td>
                    <td class="textright">52</td>
                </tr>
                <tr>
                    <td colspan="5" class="textright">TOTAL G.</td>
                    <td class="textright">1000.00</td>
                </tr>
            </tfoot>
        </table>




    </section>

    <?php include "include/footer.php"; ?>
</body>

</html>