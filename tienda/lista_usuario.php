<?php
session_start();
if ($_SESSION['rol'] != 1) {
    header("location: ./");
}

include "../conexion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Lista de Usuarios</title>
</head>

<body style="background: url('./img/fondo.jpg') no-repeat; background-size: cover; background-position: center;">
    <?php include "include/header.php"; ?>
    <section id="container">


        <h1 class="title"><i class="fa-solid fa-users"></i> Listado de Usuarios</h1>
        <a href="registro_usuario.php" class="btn_new"><i class="fa-solid fa-user-plus"></i> Crear Usuario</a>

        <form action="buscar_usuario.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <table>
            <tr>
                <th scope=""><b>Nº</b></th>
                <th scope=""><b>Nombre</b></th>
                <th scope=""><b>Usuario</b></th>
                <th scope=""><b>Correo</b></th>
                <th scope=""><b>Rol</b></th>
                <th scope=""><b>Opciones</b></th>
            </tr>
            <?php
            //Paginador
            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM usuario WHERE estatus = 1");
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

            $query = mysqli_query($conection, "SELECT u.id_usuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.id_rol WHERE estatus = 1 ORDER BY id_usuario ASC LIMIT $desde, $por_pagina");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                $index = 1;
                while ($data = mysqli_fetch_array($query)) {
            ?>
                    <tr>
                        <td><?php echo $index++ ?></td>
                        <td><?php echo $data['nombre'] ?></td>
                        <td><?php echo $data['usuario'] ?></td>
                        <td><?php echo $data['correo'] ?></td>
                        <td><?php echo $data['rol'] ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $data['id_usuario'] ?>" class="link_edit"><i class="fa-regular fa-pen-to-square"></i>Editar</a>
                            <?php
                            if ($data['id_usuario'] != 1) { ?>
                                |
                                <a href="eliminar_usuario.php?id=<?php echo $data['id_usuario'] ?>" class="link_delete"><i class="fa-regular fa-trash-can"></i> Eliminar</a>
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