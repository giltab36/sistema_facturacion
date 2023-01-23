<?php
session_start();

include "../conexion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Lista de Cliente</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if (empty($busqueda)) {
            header("location: lista_cliente.php");
        }
        ?>

        <h1 class="title">Listado de Clientes</h1>
        <a href="registro_cliente.php" class="btn_new"><i class="fa-solid fa-user-plus"></i> Crear Cliente</a>

        <form action="buscar_cliente.php" method="GET" class="form_search">
            <input type="text" class="busq" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <table>
            <tr>
                <th><b>Nº</b></th>
                <th><b>Cédula</b></th>
                <th><b>Nombre</b></th>
                <th><b>Teléfono</b></th>
                <th><b>Dirección</b></th>
                <th><b>Opciones</b></th>
            </tr>
            <?php
            //Paginador & Buscador

            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM cliente WHERE( cedula LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%' OR direccion LIKE '%$busqueda%') AND estatus = 1");
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

            $query = mysqli_query($conection, "SELECT * FROM cliente WHERE (cedula LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%' OR direccion LIKE '%$busqueda%') AND estatus = 1 ORDER BY id_cliente ASC LIMIT $desde, $por_pagina");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                $index = 1;
                while ($data = mysqli_fetch_array($query)) {
            ?>
                    <tr>
                        <td><?php echo $index++ ?></td>
                        <td><?php echo $data['cedula'] ?></td>
                        <td><?php echo $data['nombre'] ?></td>
                        <td><?php echo $data['telefono'] ?></td>
                        <td><?php echo $data['direccion'] ?></td>
                        <td>
                            <a href="editar_cliente.php?id=<?php echo $data['id_cliente'] ?>" class="link_edit"><i class="fa-regular fa-pen-to-square"></i>Editar</a>
                            <?php
                            if ($data['id_cliente'] != 1) { ?>
                                |
                                <a href="eliminar_cliente.php?id=<?php echo $data['id_cliente'] ?>" class="link_delete"><i class="fa-regular fa-trash-can"></i> Eliminar</a>
                            <?php } ?>
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
                        <li><a href="?pagina=<?php echo 1; ?>"><i class="fa-solid fa-backward-step"></i></a></li>
                        <li><a href="?pagina=<?php echo $pagina - 1; ?>"><i class="fa-solid fa-backward"></i></a></li>
                        </li>
                    <?php
                    }
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        # code
                        if ($i == $pagina) {
                            echo '<li class="pageSelected">' . $i . '</li>';
                        } else {
                            echo '<li><a href="?pagina=' . $i . '&busqueda=' . $busqueda . '">' . $i . '</a></li>';
                        }
                    }

                    if ($pagina != $total_paginas) {
                    ?>
                        <li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fa-solid fa-forward"></i></a></li>
                        <li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fa-solid fa-forward-step"></i></a></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

    </section>

    <?php include "include/footer.php"; ?>
</body>

</html>