<?php

$alert = '';

session_start();

if (!empty($_SESSION['active'])) {
    header('location: tienda/');
} else {

    if (!empty($_POST)) {
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $alert = "Ingrese su nombre de usuario y su clave";
        } else {

            require_once "conexion.php";

            $user = mysqli_real_escape_string($conection, $_POST['usuario']);
            $pass = md5(mysqli_real_escape_string($conection, $_POST['clave']));

            $query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario = '$user' AND clave = '$pass' AND estatus = 1");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                $data = mysqli_fetch_array($query);
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $data['id_usuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['email'] = $data['correo'];

                $_SESSION['user'] = $data['usuario'];
                $_SESSION['rol'] = $data['rol'];

                header('location: tienda/');
            } else {
                $alert = "El usuario o la clave son incorrectos";
                session_destroy();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion</title>
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" type="image/png" href="./images/logo.png" sizes="16x16">
</head>

<body style="background: url('./images/fondo_login_5.jpg') no-repeat; background-size: 100% 100%; background-position: center;">

    <div class="content">
        <form action="" method="post">
            <h3>Iniciar Sesión</h3>
            <img src="./images/login2.png" alt="Login">

            <input type="text" name="usuario" placeholder="Usuario">
            <input type="password" name="clave" placeholder="Contraseña">
            <div class="alert"><b><?php echo isset($alert) ? $alert : ''; ?></b></div>
            <input type="submit" value="Ingresar">

        </form>
    </div>

</body>

</html>