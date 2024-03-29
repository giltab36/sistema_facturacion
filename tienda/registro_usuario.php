<?php
session_start();
if ($_SESSION['rol'] != 1) {
    header("location: ./");
}
include "../conexion.php";

if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['usuario']) || empty($_POST['correo']) || empty($_POST['clave']) || empty($_POST['rol'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $nombre = $_POST['nombre'];
        $user = $_POST['usuario'];
        $email = $_POST['correo'];
        $pass = md5($_POST['clave']);
        $rol = $_POST['rol'];

        $query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email'");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = '<p class="msg_error">El correo o el usuario ya existe.</p>';
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO usuario (nombre, correo, usuario, clave, rol) VALUE ('$nombre', '$email', '$user', '$pass', '$rol')");

            if ($query_insert) {
                $alert = '<p class="msg_save">Usuario creado correctamente.</p>';
            } else {
                $alert = '<p class="msg_error">Error al crear el usuario.</p>';
            }
        }
    }
}

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
    <title>Registrar Usuario</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1 class="user_new"><i class="fa-solid fa-user-plus"></i> Registrar Usuario</h1>
            <hr class="hr">
            <div class="alerta"><?php echo isset($alert) ? $alert : ''; ?></div>

            <form action="" method="post">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Nombre de usuario">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" placeholder="ejemplo@gmail.com">
                <label for="clave">Contraseña</label>
                <input type="password" name="clave" id="clave" placeholder="Contraseña">
                <label for="rol">Tipo de Usuario</label>

                <?php
                $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                mysqli_close($conection);
                $result_rol = mysqli_num_rows($query_rol);
                ?>

                <select name="rol" id="rol">
                    <?php
                    if ($result_rol > 0) {
                        while ($rol = mysqli_fetch_array($query_rol)) {
                    ?>
                            <option value="<?php echo $rol["id_rol"] ?>"><?php echo $rol["rol"] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <button type="submit" class="btn_save"><i class="fa-regular fa-floppy-disk"></i> Registrar</button>

            </form>

        </div>

    </section>



    <?php 
    include "include/footer.php"; 
    ?>
</body>

</html>