<?php
session_start();

include "../conexion.php";

if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $usuario_id = $_SESSION['idUser'];

        $result = 0;
        if ((is_numeric($cedula)) and $cedula !=0){
            $query = mysqli_query($conection, "SELECT * FROM cliente WHERE cedula = '$cedula'");
            $result = mysqli_fetch_array($query);
        }
        if ($result > 0) {
            $alert = '<p class="msg_error">El numero de Cedula ya existe!</p>';
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO cliente (cedula, nombre, telefono, direccion, usuario_id) VALUE ('$cedula', '$nombre', '$telefono', '$direccion', '$usuario_id')");

            if ($query_insert) {
                $alert = '<p class="msg_save">Cliente creado correctamente.</p>';
            } else {
                $alert = '<p class="msg_error">Error al crear el Cliente.</p>';
            }
        }
    }
    mysqli_close($conection);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Registrar Cliente</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1 class="user_new">Registrar Clientes</h1>
            <hr class="hr">
            <div class="alerta"><?php echo isset($alert) ? $alert : ''; ?></div>

            <form action="" method="post">

                <label for="cedula">Cedula</label>
                <input type="number" name="cedula" id="cedula" placeholder="Numero de Cedula">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">
                <label for="telefono">Telefono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Numero de telefono">
                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion completa">

                <input type="submit" value="Registrar Cliente" class="btn_save">

            </form>

        </div>

    </section>



    <?php include "include/footer.php"; ?>
</body>

</html>