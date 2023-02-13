<?php

session_start();
include "../conexion.php";
//echo md5($_SESSION['idUser']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Registro de Ventas</title>
    <script type="text/javascript">
        $(document).ready(function() {
            var usuarioid = '<?php echo $_SESSION['idUser']; ?>'
            serchForDetalle(usuarioid)
        });
    </script>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <div class="title_page">
            <h1 class="title"><i class="fa-solid fa-dollar-sign"></i> Nueva Ventas</h1>
        </div>

        <!-- DATOS DEL CLIENTE -->
        <div class="datos_cliente">
            <div class="action_cliente">
                <h4><b>Datos del Cliente</b></h4>
                <a href="#" class="btn_new btn_new_cliente"><i class="fas fa-plus"></i> Nuevo Cliente</a>
                <a href="lista_venta.php" class="btn_new"><i class="fa-solid fa-cart-plus"></i> Lista de Venta</a>
            </div>
            <form method="POST" name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos">
                <input type="hidden" name="action" value="addCliente" readonly>
                <input type="hidden" id="idcliente" name="idcliente" value="" required readonly>
                <div class="wd30">
                    <label>Numero de Cedula o Ruc.</label>
                    <input type="text" name="ruc_cliente" id="ruc_cliente">
                </div>
                <div class="wd30">
                    <label>Nombre</label>
                    <input type="text" name="nom_cliente" id="nom_cliente" disabled required>
                </div>
                <div class="wd30">
                    <label>Teléfono</label>
                    <input type="text" name="tel_cliente" id="tel_cliente" disabled required>
                </div>
                <div class="wd100">
                    <label>Dirección</label>
                    <input type="text" name="dir_cliente" id="dir_cliente" disabled required>
                </div>
                <div id="div_registro_cliente" class="wd100">
                    <button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Guardar</button>
                </div>
            </form>
        </div>

        <!-- DATOS DE LA VENTA -->
        <div class="datos_venta">
            <h4><b>Datos de Venta</b></h4>
            <div class="datos ">
                <div class="wd50">
                    <label><b>Vendedor</b></label>
                    <p><?php echo $_SESSION['nombre']; ?></p>
                </div>
                <div clas s="wd 50">
                    <label><b>Acciones</b></label>
                    <div id="acciones_venta">
                        <a href="#" class="btn_cancel textcenter" id="btn_anular_venta"><i class="fas fa-ban"></i> Anular</a>
                        <a href="#" class="btn_new textcenter" id="btn_facturar_venta" style="display: none;"><i class="far fa-edit"></i> Procesar</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLA DE PRODUCTOS A VENDER -->
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
                    <td><input type="text" name="txt_cod_barra" id="txt_cod_barra"></td>
                    <td id="txt_descripcion">-</td>
                    <td id="txt_existencia">-</td>
                    <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
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
                <!--    CONTENIDO AJAX    -->
            </tbody>
            <tfoot class="tbl_venta line_off" id="detalle_totales">
                <!--    CONTENIDO AJAX    -->
            </tfoot>

        </table>

    </section>

    <?php include "include/footer.php"; ?>

</body>

</html>