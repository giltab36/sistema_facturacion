<?php
session_start();
include "../conexion.php";

$busqueda = '';
$where = '';
$fecha_de = '';
$fecha_a = '';

if (isset($_REQUEST['busqueda']) && $_REQUEST['busqueda'] == '') {
    header("location: lista_venta.php");
}

/* if (isset($_REQUEST['fecha_de']) && isset($_REQUEST['fecha_a'])) {
    if ($_REQUEST['fecha_de'] == '' || isset($_REQUEST['fecha_a'])) {
        header("location: lista_venta.php");
    }
} */


//  Busqueda por Nro de Factura
if (!empty($_REQUEST['busqueda'])) {
    if (!is_numeric($_REQUEST['busqueda']) && empty($_REQUEST['busqueda'])) {
        header("location: lista_venta.php");
    }
    $busqueda = strtolower($_REQUEST['busqueda']);
    $where = "no_factura = $busqueda";
    $buscar = "busqueda = $busqueda";
}

//   Busqueda por fecha
if (!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a'])) {
    $fecha_de = $_REQUEST['fecha_de'];
    $fecha_a = $_REQUEST['fecha_a'];

    $buscar = '';

    if ($fecha_de > $fecha_a) {
        header("location: lista_venta.php");
    } else if ($fecha_de == $fecha_a) {
        $where = "fecha LIKE '$fecha_de%'";
        $buscar = "fecha = $fecha_de&fecha_a=$fecha_a";
    } else {
        $f_de = $fecha_de . '00:00:00';
        $f_a = $fecha_a . '23:59:59';
        $where = "fecha BETWEEN '$f_de' AND '$f_a'";
        $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    }
}else{
    header("location: lista_venta.php");
}
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
            <input type="text" name="busqueda" id="busqueda" placeholder="Nro. Factura" value="<?php echo $busqueda; ?>">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <!-- <div>
            <h5 class="h5">Busqueda por Fecha</h5>
            <form action="buscar_venta.php" method="GET" class="form_search_date">
                <label for="fecha_de">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" value="<?php echo $fecha_de; ?>">
                <label for="fecha_a"> A </label>
                <input type="date" name="fecha_a" id="fecha_a" value="<?php echo $fecha_a; ?>">
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
                <th class="textcenter"><b>Factura-Ticket-Acciones</b></th>
            </tr>
            <?php
            //Paginador
            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM factura WHERE $where");
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
                                                WHERE $where AND f.estatus != 10 ORDER BY f.fecha DESC LIMIT $desde, $por_pagina");

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
                        <!-- <td>php //echo $index++ </td> -->
                        <td><?php echo $data['no_factura'] ?></td>
                        <td><?php echo $data['fecha'] ?></td>
                        <!-- <td>php //echo $data['hora'] </td> -->
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
        <?php
        if ($total_registro != 0) {
        ?>
            <div class="paginador">
                <ul>
                    <?php
                    if ($pagina != 1) {
                    ?>
                        <li><a href="?pagina=<?php echo 1; ?>&<?php echo $buscar; ?>"><i class="fa-solid fa-backward-step"></i></a></li>
                        <li><a href="?pagina=<?php echo $pagina - 1; ?>&<?php echo $buscar; ?>"><i class="fa-solid fa-backward"></i></a></li>
                        </li>
                    <?php
                    }
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        # code
                        if ($i == $pagina) {
                            echo '<li class="pageSelected">' . $i . '</li>';
                        } else {
                            echo '<li><a href="?pagina=' . $i . '&' . $buscar . '">' . $i . '</a></li>';
                        }
                    }

                    if ($pagina != $total_paginas) {
                    ?>
                        <li><a href="?pagina=<?php echo $pagina + 1; ?>&<?php echo $buscar; ?>"><i class="fa-solid fa-forward"></i></a></li>
                        <li><a href="?pagina=<?php echo $total_paginas; ?>&<?php echo $buscar; ?>"><i class="fa-solid fa-forward-step"></i></a></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

    </section>

    <?php include "include/footer.php"; ?>
</body>

</html>