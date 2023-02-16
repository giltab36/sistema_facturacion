<?php
session_start();
if ($_SESSION['rol'] != 1) {
    header("location: ./");
}
include "../conexion.php";

//	Datos de la Empresa
$nombreEmpresa = '';

$query_empresa = mysqli_query($conection, "SELECT nombre FROM configuracion");
$row_empesa = mysqli_num_rows($query_empresa);

if ($row_empesa > 0) {
	while ($arrayInfoEmpresa  = mysqli_fetch_assoc($query_empresa)) {
		$nombreEmpresa = $arrayInfoEmpresa['nombre'];
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Lista de Usuarios</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if (empty($busqueda)) {
            header("location: lista_usuario.php");
        }
        ?>

        <h1 class="title">Listado de Usuarios</h1>
        <a href="registro_usuario.php" class="btn_new">Crear Usuario</a>

        <form action="buscar_usuario.php" method="GET" class="form_search">
            <input type="text" class="busq" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <table>
            <tr>
                <th><b>Nº</b></th>
                <th><b>Nombre</b></th>
                <th><b>Usuario</b></th>
                <th><b>Correo</b></th>
                <th><b>Rol</b></th>
                <th><b>Opciones</b></th>
            </tr>
            <?php
            //Paginador & Buscador
            $rol = '';
            if ($busqueda == 'administrador') {
                $rol = "OR rol LIKE '%1%'";
            } else if ($busqueda == 'supervisor') {
                $rol = "OR rol LIKE '%2%'";
            } else if ($busqueda == 'vendedor') {
                $rol = "OR rol LIKE '%3%'";
            }

            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM usuario WHERE( nombre LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' OR usuario LIKE '%$busqueda%' $rol) AND estatus = 1");
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

            $query = mysqli_query($conection, "SELECT u.id_usuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.id_rol WHERE (u.nombre LIKE '%$busqueda%' OR u.correo LIKE '%$busqueda%' OR u.usuario LIKE '%$busqueda%' OR r.rol LIKE '%$busqueda%') AND estatus = 1 ORDER BY id_usuario ASC LIMIT $desde, $por_pagina");
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