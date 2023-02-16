<?php
session_start();

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

        <?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if (empty($busqueda)) {
            header("location: lista_proveedor.php");
        }
        ?>

        <h1 class="title"><i class="fa-solid fa-users"></i> Listado de Proveedores</h1>
        <a href="registro_proveedor.php" class="btn_new"><i class="fa-solid fa-plus"></i> Agregar Proveedor</a>

        <form action="buscar_proveedor.php" method="GET" class="form_search">
            <input type="text" class="busq" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
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
            //Paginador & Buscador

            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM proveedor WHERE( proveedor LIKE '%$busqueda%' OR contacto LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%' OR direccion LIKE '%$busqueda%') AND estatus = 1");
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

            $query = mysqli_query($conection, "SELECT * FROM proveedor WHERE (proveedor LIKE '%$busqueda%' OR contacto LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%' OR direccion LIKE '%$busqueda%') AND estatus = 1 ORDER BY cod_proveedor ASC LIMIT $desde, $por_pagina");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                $index = 1;
                while ($data = mysqli_fetch_array($query)) {
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