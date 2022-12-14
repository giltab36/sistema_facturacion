<?php
session_start();


include "../conexion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Lista de Productos</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">


        <h1 class="title"><i class="fa-solid fa-boxes-stacked"></i> Listado de Productos</h1>
        <a href="registro_producto.php" class="btn_new"><i class="fa-solid fa-truck-ramp-box"></i> Agregar Producto</a>

        <form action="buscar_cliente.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <table>
            <tr>
                <th scope=""><b>Nº</b></th>
                <th scope=""><b>Proveedor</b></th>
                <th scope=""><b>Producto</b></th>
                <th scope=""><b>Precio</b></th>
                <th scope=""><b>Cantidad</b></th>
                <th scope=""><b>Foto</b></th>
                <th scope=""><b>Opciones</b></th>
            </tr>
            <?php
            //Paginador
            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM producto WHERE estatus = 1");
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

            $query = mysqli_query($conection, "SELECT cod_producto, descripcion, precio, existencia, foto, pto.proveedor, pdor.proveedor FROM producto pto INNER JOIN proveedor pdor ON pto.proveedor = pdor.cod_proveedor WHERE pto.estatus = 1 ORDER BY cod_producto DESC LIMIT $desde, $por_pagina");
            $result = mysqli_num_rows($query);
            mysqli_close($conection);

            if ($result > 0) {
                $index = 1;
                while ($data = mysqli_fetch_array($query)) {
                    if ($data['foto'] != 'img_producto.png') {
                        $foto = 'img/uploads/' . $data['foto'];
                    } else {
                        $foto = 'img/' . $data['foto'];
                    }
            ?>
                    <tr>
                        <td><?php echo $index++ ?></td>
                        <td><?php echo $data['proveedor'] ?></td>
                        <td><?php echo $data['descripcion'] ?></td>
                        <td><?php echo $data['precio'] ?></td>
                        <td><?php echo $data['existencia'] ?></td>
                        <td class="img_producto"><img src="<?php echo $foto ?>" alt="<?php echo $data['descripcion'] ?>"></td>

                        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
                            <td>
                                <a product="<?php echo $data['cod_producto']; ?>" class="link_add add_product" href="#"><i class="fa-solid fa-plus"></i> Agregar</a>
                                |
                                <a href="editar_producto.php?id=<?php echo $data['cod_producto'] ?>" class="link_edit"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                                |
                                <a href="eliminar_producto.php?id=<?php echo $data['cod_producto'] ?>" class="link_delete"><i class="fa-regular fa-trash-can"></i> Eliminar</a>
                            </td>
                        <?php } ?>
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