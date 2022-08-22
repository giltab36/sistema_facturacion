<?php
include "../conexion.php";

if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['usuario']) || empty($_POST['correo']) || empty($_POST['rol'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $idusuario = $_POST['id_usuario'];
        $nombre = $_POST['nombre'];
        $user = $_POST['usuario'];
        $email = $_POST['correo'];
        $pass = md5($_POST['clave']);
        $rol = $_POST['rol'];

        $query = mysqli_query($conection, "SELECT * FROM usuario WHERE (usuario = '$user' AND id_usuario != $idusuario) OR (correo = '$email' AND id_usuario != $idusuario)");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = '<p class="msg_error">El correo o el usuario ya existe.</p>';
        } else {

            if(empty($_POST['clave'])){
                $sql_update = mysqli_query($conection, "UPDATE usuario SET nombre = '$nombre', correo = '$email', usuario = '$user', rol = '$rol' WHERE id_usuario = $idusuario");
            }else{
                $sql_update = mysqli_query($conection, "UPDATE usuario SET nombre = '$nombre', correo = '$email', clave = '$pass', usuario = '$user', rol = '$rol' WHERE id_usuario = $idusuario");
            }

            if ($sql_update) {
                $alert = '<p class="msg_save">Usuario editado correctamente.</p>';
            } else {
                $alert = '<p class="msg_error">Error al editar el usuario.</p>';
            }
        }
    }
}


//Mostrar Datos
if (empty($_GET['id'])) {
    header('Location: lista_usuario.php');
}

$iduser = $_GET['id'];
$sql = mysqli_query($conection, "SELECT u.id_usuario, u.nombre, u.correo, u.usuario, (u.rol) AS id_rol, (r.rol) AS rol FROM usuario u INNER JOIN rol r ON u.rol = r.id_rol WHERE id_usuario = $iduser");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('Location: lista_usuario.php');
} else {
    $option = '';
    while ($data = mysqli_fetch_array($sql)) {
        $iduser = $data['id_usuario'];
        $nombre = $data['nombre'];
        $email = $data['correo'];
        $user = $data['usuario'];
        $id_rol = $data['id_rol'];
        $rol = $data['rol'];

        if ($id_rol == 1) {
            $option = '<option value="' . $id_rol . '" select>' . $rol . '</option>';
        } else if ($id_rol == 2) {
            $option = '<option value="' . $id_rol . '" select>' . $rol . '</option>';
        } else if ($id_rol == 3) {
            $option = '<option value="' . $id_rol . '" select>' . $rol . '</option>';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Editar Usuario</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1 class="user_new">Editar Usuario</h1>
            <hr class="hr">
            <div class="alerta"><?php echo isset($alert) ? $alert : ''; ?></div>

            <form action="" method="post">

                    <input type="hidden" name="id_usuario" value="<?php echo $iduser; ?>">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombre; ?>">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Nombre de usuario" value="<?php echo $user; ?>">

                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" placeholder="ejemplo@gmail.com" value="<?php echo $email; ?>">

                <label for="clave">Contraseña</label>
                <input type="password" name="clave" id="clave" placeholder="Contraseña" readonly>

                <label for="rol">Tipo de Usuario</label>
                <?php
                $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                $result_rol = mysqli_num_rows($query_rol);
                ?>
                <select name="rol" id="rol" class="notitemone">
                    <?php
                    echo $option;
                    if ($result_rol > 0) {
                        while ($rol = mysqli_fetch_array($query_rol)) {
                    ?>
                            <option value="<?php echo $rol["id_rol"] ?>"><?php echo $rol["rol"] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <input type="submit" value="Modificar" class="btn_save">

            </form>

        </div>

    </section>



    <?php include "include/footer.php"; ?>
</body>

</html>