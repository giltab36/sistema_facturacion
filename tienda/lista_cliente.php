<?php
session_start();

include "../conexion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Lista de Clientes</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">


        <h1 class="title">Listado de Clientes</h1>
        <a href="registro_cliente.php" class="btn_new">Crear Cliente</a>

        <form action="buscar_usuario.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <input type="submit" value="Buscar" class="btn_search">
        </form>

        <table>
            <tr>
                <th scope=""><b>Nº</b></th>
                <th scope=""><b>Cédula</b></th>
                <th scope=""><b>Nombre</b></th>
                <th scope=""><b>Teléfono</b></th>
                <th scope=""><b>Dirección</b></th>
                <th scope=""><b>Opciones</b></th>
            </tr>
            <?php
            //Paginador
            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM cliente WHERE estatus = 1");
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

            $query = mysqli_query($conection, "SELECT * FROM cliente WHERE estatus = 1 ORDER BY id_cliente ASC LIMIT $desde, $por_pagina");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                $index = 1;
                while ($data = mysqli_fetch_array($query)) {
                    if ($data["cedula"] == 0){
                        $nit = 'C/F';
                    }else{
                        $nit = $data["cedula"];
                    }
            ?>
                    <tr>
                        <td><?php echo $index++ ?></td>
                        <td><?php echo $nit; ?></td>
                        <td><?php echo $data['nombre'] ?></td>
                        <td><?php echo $data['telefono'] ?></td>
                        <td><?php echo $data['direccion'] ?></td>
                        <td>
                            <a href="editar_cliente.php?id=<?php echo $data['id_cliente'] ?>" class="link_edit"><i class="fas fa-pen">Editar</i></a>
                            |
                            <a href="eliminar_cliente.php?id=<?php echo $data['id_cliente'] ?>" class="link_delete"><i class="fas fa-trash">Eliminar</i></a>
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