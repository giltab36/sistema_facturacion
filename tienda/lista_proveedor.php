<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
}

include "../conexion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Lista de Proveedores</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">


        <h1 class="title"><i class="fa-solid fa-users"></i> Listado de Proveedores</h1>
        <a href="registro_proveedor.php" class="btn_new"><i class="fa-solid fa-user-plus"></i> Agregar Proveedor</a>

        <form action="buscar_cliente.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <table>
            <tr>
                <th scope=""><b>Nº</b></th>
                <th scope=""><b>Proveedor</b></th>
                <th scope=""><b>Contacto</b></th>
                <th scope=""><b>Teléfono</b></th>
                <th scope=""><b>Dirección</b></th>
                <th scope=""><b>Opciones</b></th>
            </tr>
            <?php
            //Paginador
            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM proveedor WHERE estatus = 1");
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

            $query = mysqli_query($conection, "SELECT * FROM proveedor WHERE estatus = 1 ORDER BY cod_proveedor ASC LIMIT $desde, $por_pagina");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                $index = 1;
                while ($data = mysqli_fetch_array($query)) {
                    if ($data["cod_proveedor"] == 0) {
                        $nit = 'C/F';
                    } else {
                        $nit = $data["cod_proveedor"];
                    }
            ?>
                    <tr>
                        <td><?php echo $index++ ?></td>
                        <td><?php echo $data['proveedor'] ?></td>
                        <td><?php echo $data['contacto'] ?></td>
                        <td><?php echo $data['telefono'] ?></td>
                        <td><?php echo $data['direccion'] ?></td>
                        <td>
                            <a href="editar_proveedor.php?id=<?php echo $data['cod_proveedor'] ?>" class="link_edit"><i class="fa-regular fa-pen-to-square"></i>Editar</a>
                            <?php if ($_SESSION['rol'] == 1 /*|| $_SESSION['rol'] == 2*/) { ?>
                                |
                                <a href="eliminar_proveedor.php?id=<?php echo $data['cod_proveedor'] ?>" class="link_delete"><i class="fa-regular fa-trash-can"></i> Eliminar</a>
                            <?php } ?>
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