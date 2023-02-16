<?php
session_start();
include "../conexion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Lista de Ventas</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">


        <h1 class="title"><i class="fa-solid fa-list-check"></i> Listado de Ventas</h1>
        <a href="registro_venta.php" class="btn_new"><i class="fa-solid fa-cart-plus"></i> Crear Nueva Venta</a>

        <form action="buscar_venta.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Nro. Factura" >
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <!-- <div>
            <h5 class="h5">Busqueda por Fecha</h5>
            <form action="buscar_venta.php" method="GET" class="form_search_date">
                <label for="fecha_de">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" >
                <label for="fecha_a"> A </label>
                <input type="date" name="fecha_a" id="fecha_a" >
                <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
            </form>
        </div> -->

        <table>
            <tr>
                <th><b>Nº</b></th>
                <th><b>Fecha / Hora</b></th>
                <!-- <th><b>Hora</b></th> -->
                <th><b>Cliente</b></th>
                <th><b>Vendedor</b></th>
                <th><b>Estado</b></th>
                <th class="textright"><b>Total Factura</b></th>
                <th class="textcenter"><b>Factura - Ticket - Acciones</b></th>
            </tr>
            <?php
            //Paginador
            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM factura WHERE estatus != 10");
            $result_register = mysqli_fetch_array($sql_register);
            $total_registro = $result_register['total_registro'];

            $por_pagina = 10;

            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }

            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);

            $query = mysqli_query($conection, "SELECT f.no_factura, f.fecha /* DATE_FORMAT(f.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.fecha,'%H:%i:%s') as  hora */, f.total_factura, f.cod_cliente, f.estatus, u.nombre AS vendedor, cl.nombre AS cliente 
                                                FROM factura f 
                                                INNER JOIN usuario u ON f.usuario = u.id_usuario
                                                INNER JOIN cliente cl ON f.cod_cliente = cl.id_cliente
                                                WHERE f.estatus != 10 ORDER BY f.fecha DESC LIMIT $desde, $por_pagina");

            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                //$index = 1;
                while ($data = mysqli_fetch_array($query)) {
                    if ($data["estatus"] == 1) {
                        $estado = '<span class="pagada">Pagada</span>';
                    } else {
                        $estado = '<span class="anulada">Anulada</span>';
                    }
            ?>
                    <tr id="row <?php echo $data["no_factura"]; ?>">
                        <!-- <td> //echo $index++ </td> -->
                        <td><?php echo $data['no_factura'] ?></td>
                        <td><?php echo $data['fecha'] ?></td>
                        <!-- <td>php echo $data['hora'] </td> -->
                        <td><?php echo $data['cliente'] ?></td>
                        <td><?php echo $data['vendedor'] ?></td>
                        <td class="estado"><?php echo $estado ?></td>
                        <td class="textright totalfactura"><span>₲.</span><?php echo $data["total_factura"]; ?></td>
                        <td class="textright">
                            <div class="div_acciones">
                                <div>
                                    <button class="btn_ver view_factura" type="button" cl="<?php echo $data["cod_cliente"]; ?>" f="<?php echo $data["no_factura"] ?>"><i class="fas fa-eye"></i></button>
                                    <button class="btn_ver view_ticket" type="button" cl="<?php echo $data["cod_cliente"]; ?>" f="<?php echo $data["no_factura"] ?>"><i class="fas fa-eye"></i></button>
                                </div>

                                <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                                    if ($data["estatus"] == 1) { ?>
                                        <div class="div_factura">
                                            <div>
                                                <button class="btn_anular anular_factura" fac="<?php echo $data['no_factura'] ?>"><i class="fas fa-ban"></i></button>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="div_factura">
                                            <div>
                                                <button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>
                                            </div>
                                        </div>
                                <?php }
                                } ?>
                            </div>
                        </td>
                    </tr>
            <?php
                }
            }

            ?>
        </table>

        <!--Paginación-->
        <?php include('./include/pag.php'); ?>

    </section>

    <?php include "include/footer.php"; ?>
</body>

</html>