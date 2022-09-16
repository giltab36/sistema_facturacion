<?php
session_start();
if ($_SESSION['rol'] != 1) {
    header("location: ./");
}
include "../conexion.php";

if (!empty($_POST)) {

    if ($_POST['id_usuario'] == 1) {
        header('location: lista_usuario.php');
        mysqli_close($conection);
    }
    $idusuario = $_POST['id_usuario'];
    //$query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE id_usuario = $idusuario");
    $query_delete = mysqli_query($conection, "UPDATE usuario SET estatus = 0 WHERE id_usuario = $idusuario");

    if ($query_delete) {
        header('location: lista_usuario.php');
    } else {
        echo "Error al eliminar los datos";
    }
}

if (empty($_REQUEST['id']) || $_REQUEST['id'] == 1) {
    header('location: lista_usuario.php');
    mysqli_close($conection);
} else {
    $idusuario = $_REQUEST['id'];

    $query = mysqli_query($conection, "SELECT u.nombre, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.id_rol WHERE u.id_usuario = $idusuario");
    mysqli_close($conection);
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $nombre = $data['nombre'];
            $usuario = $data['usuario'];
            $rol = $data['rol'];
        }
    } else {
        header('location: lista_usuario.php');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Confirmar Eliminacion</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">
        <div class="data_delete">
            <i class="fa-solid fa-user-xmark fa-7x" style="color: silver;"></i>
            <br>
            <br>
            <h2><b>¿Seguro que desea eliminar estos datos?</b></h2>
            <p><b>Nombre:</b> <span><?php echo $nombre; ?></span></p>
            <p><b>Usuario:</b> <span><?php echo $usuario; ?></span></p>
            <p><b>Tipo de usuario:</b> <span><?php echo $rol; ?></span></p>
            <form action="" method="POST">
                <input type="hidden" name="id_usuario" value="<?php echo $idusuario; ?>">
                <a href="lista_usuario.php" class="btn_cancel"><i class="fa-solid fa-xmark"></i> Cancelar</a>
                <button type="submit" class="btn_ok"><i class="fa-solid fa-check"></i> Eliminar</button>
            </form>
        </div>
    </section>



    <?php include "include/footer.php"; ?>
</body>

</html>