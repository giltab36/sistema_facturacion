<?php
session_start();

include "../conexion.php";

if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $idcliente = $_POST['id'];
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];

        $result = 0;
        if (is_numeric($cedula) and $cedula != 0) {
            $query = mysqli_query($conection, "SELECT * FROM cliente WHERE (cedula = '$cedula' AND id_cliente != $idcliente)");
            $result = mysqli_fetch_array($query);
            //$result = count($result);
        }

        if ($result > 0) {
            $alert = '<p class="msg_error">La cedula ya existe, ingrese otro.</p>';
        } else {

            if ($cedula == '') {
                $cedula = 0;
            }
            $sql_update = mysqli_query($conection, "UPDATE cliente SET cedula = $cedula, nombre = '$nombre', telefono = '$telefono', direccion = '$direccion' WHERE id_cliente = $idcliente");

            if ($sql_update) {
                $alert = '<p class="msg_save">Cliente editado correctamente.</p>';
            } else {
                $alert = '<p class="msg_error">Error al editar el cliente.</p>';
            }
        }
    }
}


//Mostrar Datos
if (empty($_REQUEST['id'])) {
    header('Location: lista_cliente.php');
    mysqli_close($conection);
}

$idcliente = $_REQUEST['id'];
$sql = mysqli_query($conection, "SELECT * FROM cliente WHERE id_cliente = $idcliente");
mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('Location: lista_cliente.php');
} else {

    while ($data = mysqli_fetch_array($sql)) {
        $idcliente = $data['id_cliente'];
        $cedula = $data['cedula'];
        $nombre = $data['nombre'];
        $telefono = $data['telefono'];
        $direccion = $data['direccion'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Editar Clientes</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1 class="user_new"><i class="fa-regular fa-pen-to-square"></i> Editar Cliente</h1>
            <hr class="hr">
            <div class="alerta"><?php echo isset($alert) ? $alert : ''; ?></div>

            <form action="" method="post">

                <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
                <label for="cedula">Cedula</label>
                <input type="number" name="cedula" id="cedula" placeholder="Numero de Cedula" value="<?php echo $cedula; ?>">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombre; ?>">
                <label for="telefono">Telefono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Numero de telefono" value="<?php echo $telefono; ?>">
                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion completa" value="<?php echo $direccion; ?>">

                <button type="submit" class="btn_save"><i class="fa-regular fa-pen-to-square"></i> Modificar</button>


            </form>

        </div>

    </section>



    <?php include "include/footer.php"; ?>
</body>

</html>